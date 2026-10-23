<?php

namespace App\Libraries\Legacy;

/** $this->output gaya CI3: menulis ke objek Response CI4 yang dipakai framework. */
class LegacyOutput
{
    private function r()
    {
        return service('response');
    }

    public function set_content_type(string $mime, ?string $charset = null): self
    {
        $this->r()->setContentType($mime, $charset ?? 'UTF-8');

        return $this;
    }

    public function set_status_header(int $code = 200, string $text = ''): self
    {
        $this->r()->setStatusCode($code, $text === '' ? '' : $text);

        return $this;
    }

    public function set_header(string $header, bool $replace = true): self
    {
        if (strpos($header, ':') !== false) {
            [$name, $value] = array_map('trim', explode(':', $header, 2));
            $this->r()->setHeader($name, $value);
        }

        return $this;
    }

    public function set_output($output): self
    {
        Legacy::setFinalOutput((string) $output);   // diterapkan filter akhir (PolishCss) agar tidak ditimpa buffer

        return $this;
    }

    public function append_output($output): self
    {
        echo (string) $output;

        return $this;
    }

    public function get_output(): string
    {
        return Legacy::finalOutput() ?? '';
    }
}
