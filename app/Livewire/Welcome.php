<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

use App\Models\Announcement;

class Welcome extends Component
{
    #[Computed]
    public function announcements()
    {
        return Announcement::where('is_active', true)
            ->get();
    }

    #[Title('Welcome')]
    public function render()
    {
        return view('livewire.welcome');
    }
}
