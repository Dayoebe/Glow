<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use Livewire\Component;

class NewsShow extends Component
{
    public News $news;

    public bool $canManage = false;

    public bool $publiclyVisible = false;

    public function mount(int $id): void
    {
        $this->news = News::with(['author', 'category', 'reviewedBy'])->findOrFail($id);

        $user = auth()->user();
        $this->canManage = $user
            && ($user->canApproveNews() || (int) $this->news->author_id === (int) $user->id);

        $this->publiclyVisible = News::published()
            ->whereKey($this->news->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.admin.news.show')->layout('layouts.admin', [
            'header' => 'News Details',
        ]);
    }
}
