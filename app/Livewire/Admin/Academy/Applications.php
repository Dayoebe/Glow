<?php

namespace App\Livewire\Admin\Academy;

use App\Livewire\Admin\Career\CareerApplications;

class Applications extends CareerApplications
{
    public function mount(?string $type = null): void
    {
        $this->academyWorkspace = true;
        $this->applicationType = 'academy';
    }
}
