<?php

namespace StatisticLv\AdminPanel\Interfaces;

interface MenuRendererInterface
{
    /**
     * Render a menu as HTML.
     *
     * @param string $identifier
     * @param string $type
     * @param array $options
     * @return string
     */
    public function render(string $identifier, string $type = 'slug', array $options = []): string;
}
