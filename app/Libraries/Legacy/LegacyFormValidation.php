<?php

namespace App\Libraries\Legacy;

/**
 * Subset CI_Form_validation (CI3) untuk aturan yang dipakai aplikasi:
 * required, trim, max_length[], min_length[], exact_length[], in_list[], numeric,
 * integer, valid_email, greater_than[], less_than[], matches[], alpha_numeric,
 * serta fungsi PHP praproses (strtolower, strip_tags, ...). Satu pesan per bidang,
 * pesan bahasa Inggris seperti CI3 supaya tampilan tidak berubah.
 */
class LegacyFormValidation
{
    private LegacyInput $input;
    /** @var array<string,array{label:string,rules:list<string>}> */
    private array $fields = [];
    private array $errors = [];

    public function __construct(LegacyInput $input)
    {
        $this->input = $input;
    }

    public function set_rules($field, string $label = '', $rules = ''): self
    {
        if (is_array($field)) {
            foreach ($field as $row) {
                $this->set_rules($row['field'], $row['label'] ?? '', $row['rules'] ?? '');
            }

            return $this;
        }
        $this->fields[$field] = [
            'label' => $label !== '' ? $label : $field,
            'rules' => is_array($rules) ? $rules : array_values(array_filter(explode('|', (string) $rules), static fn ($r) => $r !== '')),
        ];

        return $this;
    }

    public function run(string $group = ''): bool
    {
        $this->errors = [];
        if ($this->fields === [] || $this->input->method() !== 'post') {
            return false;
        }

        foreach ($this->fields as $name => $def) {
            $value    = $this->input->post($name);
            $required = in_array('required', $def['rules'], true);

            foreach ($def['rules'] as $rule) {
                $param = null;
                if (preg_match('/^(.*?)\[(.*)\]$/', $rule, $m)) {
                    $rule  = $m[1];
                    $param = $m[2];
                }

                if ($rule === 'required') {
                    if (is_array($value) ? $value === [] : trim((string) $value) === '') {
                        $this->errors[$name] = $this->pesan('required', $def['label']);
                        break;
                    }
                    continue;
                }
                if ($rule === 'trim') {
                    if (is_string($value)) {
                        $value = trim($value);
                        $this->input->set_post($name, $value);
                    }
                    continue;
                }
                // praproses fungsi PHP biasa (strtolower, strip_tags, ...)
                if (! $this->aturanValidasi($rule) && function_exists($rule)) {
                    if (is_string($value)) {
                        $value = $rule($value);
                        $this->input->set_post($name, $value);
                    }
                    continue;
                }

                // bidang kosong yang tidak wajib: lewati pemeriksaan
                if (! $required && (is_array($value) ? $value === [] : ($value === null || $value === ''))) {
                    continue;
                }

                $teks = is_array($value) ? '' : (string) $value;
                $salah = false;
                switch ($rule) {
                    case 'max_length':
                        $salah = mb_strlen($teks) > (int) $param;
                        break;
                    case 'min_length':
                        $salah = mb_strlen($teks) < (int) $param;
                        break;
                    case 'exact_length':
                        $salah = mb_strlen($teks) !== (int) $param;
                        break;
                    case 'in_list':
                        $salah = ! in_array($teks, explode(',', (string) $param), true);
                        break;
                    case 'numeric':
                        $salah = ! is_numeric($teks);
                        break;
                    case 'integer':
                        $salah = ! preg_match('/^[\-+]?[0-9]+$/', $teks);
                        break;
                    case 'is_natural':
                        $salah = ! ctype_digit($teks);
                        break;
                    case 'valid_email':
                        $salah = ! filter_var($teks, FILTER_VALIDATE_EMAIL);
                        break;
                    case 'greater_than':
                        $salah = ! is_numeric($teks) || (float) $teks <= (float) $param;
                        break;
                    case 'less_than':
                        $salah = ! is_numeric($teks) || (float) $teks >= (float) $param;
                        break;
                    case 'matches':
                        $salah = $teks !== (string) $this->input->post($param);
                        break;
                    case 'alpha_numeric':
                        $salah = ! ctype_alnum($teks);
                        break;
                    default:
                        throw new \RuntimeException('LegacyFormValidation: aturan belum didukung: ' . $rule);
                }
                if ($salah) {
                    $this->errors[$name] = $this->pesan($rule, $def['label'], $param);
                    break;
                }
            }
        }

        return $this->errors === [];
    }

    private function aturanValidasi(string $rule): bool
    {
        return in_array($rule, ['max_length', 'min_length', 'exact_length', 'in_list', 'numeric', 'integer', 'is_natural', 'valid_email', 'greater_than', 'less_than', 'matches', 'alpha_numeric'], true);
    }

    private function pesan(string $rule, string $label, ?string $param = null): string
    {
        $p = [
            'required'     => 'The %s field is required.',
            'max_length'   => 'The %s field cannot exceed %s characters in length.',
            'min_length'   => 'The %s field must be at least %s characters in length.',
            'exact_length' => 'The %s field must be exactly %s characters in length.',
            'in_list'      => 'The %s field must be one of: %s.',
            'numeric'      => 'The %s field must contain only numbers.',
            'integer'      => 'The %s field must contain an integer.',
            'is_natural'   => 'The %s field must only contain digits.',
            'valid_email'  => 'The %s field must contain a valid email address.',
            'greater_than' => 'The %s field must contain a number greater than %s.',
            'less_than'    => 'The %s field must contain a number less than %s.',
            'matches'      => 'The %s field does not match the %s field.',
            'alpha_numeric' => 'The %s field may only contain alpha-numeric characters.',
        ][$rule] ?? 'The %s field is not valid.';

        return sprintf($p, $label, $param);
    }

    public function error(string $field = '', string $open = '<p>', string $close = '</p>'): string
    {
        return isset($this->errors[$field]) ? $open . $this->errors[$field] . $close : '';
    }

    public function error_array(): array
    {
        return $this->errors;
    }

    public function error_string(string $open = '<p>', string $close = '</p>'): string
    {
        $out = '';
        foreach ($this->errors as $e) {
            $out .= $open . $e . $close . "\n";
        }

        return $out;
    }

    public function reset_validation(): self
    {
        $this->fields = [];
        $this->errors = [];

        return $this;
    }
}
