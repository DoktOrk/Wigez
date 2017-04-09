<?php

namespace Foo\Grid\Helper;

use Foo\Grid\Component\IComponent;

class StringHelper
{
    /**
     * @param string|IComponent $content
     * @param string|null       $tag
     * @param array             $attributes
     * @param string            $whitespace
     *
     * @return string
     */
    public static function wrapInTag($content, string $tag = null, array $attributes = [], $whitespace = '')
    {
        if (null === $tag) {
            return (string)$content;
        }

        $attributeHtml = '';

        foreach ($attributes as $key => $value) {
            $attributeHtml .= sprintf(' %s="%s"', $key, $value);
        }

        if ($whitespace) {
            return sprintf(
                '%4$s<%1$s%3$s>%5$s%2$s%5$s%4$s</%1$s>',
                $tag,
                (string)$content,
                $attributeHtml,
                $whitespace,
                "\n"
            );
        }

        return sprintf('<%1$s%3$s>%2$s</%1$s>', $tag, (string)$content, $attributeHtml);
    }
}
