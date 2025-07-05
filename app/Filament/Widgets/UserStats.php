<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Role;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        $users = User::all();

        return [
            Stat::make('Total Users', $users->count())
                ->description('Total pengguna terdaftar')
                ->color('primary')
                ->icon('heroicon-o-users'),
            Stat::make('Total Pengajar', $users->where('role_id', Role::IS_PENGAJAR)->count())
                ->description('Total pengajar terdaftar')
                ->color('primary')
                ->icon('heroicon-o-users'),
            Stat::make('Total Pelajar', $users->where('role_id', Role::IS_PELAJAR)->count())
                ->description('Total Pelajar terdaftar')
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role_id == Role::IS_ADMINISTRATOR;
    }
}
