<?php

namespace App\Admin;

/**
 * Interface for widgets in admin dashboard
 */
interface AdminWidgetInterface
{
    public function render(): string;

    public function renderMenu(): string;
}
