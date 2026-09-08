"""Convert the supplied zoning shapefiles to compact web GeoJSON."""

from __future__ import annotations

import json
import sys
from pathlib import Path

import shapefile


def simplify_ring(points, tolerance):
    """Fast radial-distance simplification for a closed polygon ring."""
    if len(points) <= 5:
        return [[round(p[0], 6), round(p[1], 6)] for p in points]

    tolerance_sq = tolerance * tolerance
    simplified = []
    previous = None
    for point in points[:-1]:
        xy = [round(point[0], 6), round(point[1], 6)]
        if previous is None or (
            (xy[0] - previous[0]) ** 2 + (xy[1] - previous[1]) ** 2
        ) >= tolerance_sq:
            simplified.append(xy)
            previous = xy

    if len(simplified) < 3:
        simplified = [
            [round(points[index][0], 6), round(points[index][1], 6)]
            for index in (0, len(points) // 3, (len(points) * 2) // 3)
        ]
    simplified.append(simplified[0])
    return simplified


def signed_area(ring):
    return sum(
        ring[index][0] * ring[index + 1][1]
        - ring[index + 1][0] * ring[index][1]
        for index in range(len(ring) - 1)
    ) / 2


def shape_geometry(source_shape, tolerance):
    """Build GeoJSON directly, avoiding pyshp's costly polygon inspection."""
    part_starts = list(source_shape.parts) + [len(source_shape.points)]
    rings = [
        simplify_ring(source_shape.points[start:end], tolerance)
        for start, end in zip(part_starts, part_starts[1:])
        if end - start >= 4
    ]

    polygons = []
    for ring in rings:
        # ESRI outer rings are clockwise (negative signed area).
        if signed_area(ring) < 0 or not polygons:
            polygons.append([ring])
        else:
            polygons[-1].append(ring)

    if len(polygons) == 1:
        return {"type": "Polygon", "coordinates": polygons[0]}
    return {"type": "MultiPolygon", "coordinates": polygons}


def convert(source: Path, destination: Path, tolerance: float = 0.00025) -> None:
    reader = shapefile.Reader(str(source), encoding="utf-8")
    features = []

    for item in reader.iterShapeRecords():
        geometry = shape_geometry(item.shape, tolerance)

        properties = {
            key: value
            for key, value in item.record.as_dict().items()
            if value not in (None, "")
        }
        features.append(
            {
                "type": "Feature",
                "properties": properties,
                "geometry": geometry,
            }
        )

    destination.parent.mkdir(parents=True, exist_ok=True)
    destination.write_text(
        json.dumps(
            {"type": "FeatureCollection", "features": features},
            ensure_ascii=False,
            separators=(",", ":"),
        ),
        encoding="utf-8",
    )
    print(f"{source.name}: {len(features)} features -> {destination}")


if __name__ == "__main__":
    if len(sys.argv) != 3:
        raise SystemExit("usage: convert_spatial_shp.py SOURCE_DIR OUTPUT_DIR")

    source_dir = Path(sys.argv[1])
    output_dir = Path(sys.argv[2])
    convert(source_dir / "Pola_Ruang.shp", output_dir / "pola-ruang.geojson")
    convert(source_dir / "LP2B.shp", output_dir / "lp2b.geojson")
