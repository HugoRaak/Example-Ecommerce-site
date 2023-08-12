<?php declare(strict_types=1);
namespace Framework\Twig;

use Twig\Extension\AbstractExtension;

final class FormExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('field', [$this, 'field'], [
                'is_safe' => ['html'],
                'needs_context' => true
            ])
        ];
    }

    /**
     * get a input for a form
     * @param mixed[] $context
     * @param mixed[] $attributes
     *
     */
    public function field(array $context, string $key, mixed $value, string $label = '', array $attributes = []): string
    {
        $cleanKey = str_replace('[]', '', $key);
        $attributes['type'] = $attributes['type'] ?? 'text';
        $attributes += [
            'class' => 'form-control ' . ($attributes['class'] ?? ''),
            'name' => $key,
            'id' => $cleanKey
        ];
        $errorsFeedback = '';
        if (isset($context['errors'][$cleanKey])) {
            $errorsFeedback = '<div class="invalid-feedback">' .
                                    implode('<br>', $context['errors'][$cleanKey]) .
                              '</div>';
            $attributes['class'] .= ' is-invalid';
        }
        if ($attributes['type'] === 'textarea') {
            unset($attributes['type']);
            $input = $this->textarea($value, $attributes);
        } elseif ($attributes['type'] === 'select') {
            unset($attributes['type']);
            $input = $this->selectCategories($value, $attributes);
        } elseif ($attributes['type'] === 'file') {
            $input = $this->fileInput($attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        return <<<HTML
        <div class="form-group">
            <label for="{$key}">{$label}</label>
            {$input}
            {$errorsFeedback}
        </div>
        HTML;
    }

    /**
     * get a input
     * @param mixed[] $attributes
     *
     */
    private function input(mixed $value, array $attributes = []): string
    {
        return '<input value="' . $value . '" ' . $this->getAttributes($attributes) . '>';
    }

    /**
     * get a textarea input
     * @param mixed[] $attributes
     *
     */
    private function textarea(mixed $value, array $attributes = []): string
    {
        return '<textarea ' . $this->getAttributes($attributes) . '>' . $value . '</textarea>';
    }

    /**
     * get a file input
     * @param mixed[] $attributes
     *
     */
    private function fileInput(array $attributes = []): string
    {
        return '<input ' . $this->getAttributes($attributes) . '>';
    }

    /**
     * get a select input
     * @param int|null $value
     * @param mixed[] $attributes
     *
     */
    private function selectCategories(?int $value, array $attributes = []): string
    {
        $options = array_reduce(
            array_keys($attributes['values']),
            function (string $html, int|string $id) use ($value, $attributes) {
                $params = ['value' => $id, 'selected' => (int)$id === $value];
                return $html . '<option ' . $this->getAttributes($params) . '>'
                    . $attributes['values'][$id] . '</option>';
            },
            ''
        );
        if ($value ===  null) {
            $options = '<option value="null" selected></option>' . $options;
        }
        unset($attributes['values']);
        return '<select ' . $this->getAttributes($attributes) . '>' . $options . '</select>';
    }

    /**
     * retrieve the different attributes for an input
     * @param mixed[] $attributes
     *
     */
    private function getAttributes(array $attributes): string
    {
        return implode(' ', array_map(function ($key, $value) {
            if ($value === true) {
                return $key;
            } elseif ($value !== false) {
                return $key . '="' . $value . '"';
            }
        }, array_keys($attributes), $attributes));
    }
}
