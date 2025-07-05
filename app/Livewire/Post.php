<?php

namespace App\Livewire;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\Title;

class Post extends Component
{
    public Announcement $post;
 
    public function mount($id)
    {
        $this->post = Announcement::findOrFail($id);
    }

    #[Title('Announcement')]
    public function render()
    {
        return view('livewire.post');
    }
}
