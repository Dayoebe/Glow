<?php

namespace App\Livewire\Concerns;

trait RemembersAdminPagination
{
    public function mountRemembersAdminPagination(): void
    {
        if (! request()->hasSession()) {
            return;
        }

        foreach (session()->get($this->adminPaginationSessionKey(), []) as $pageName => $page) {
            if (! is_string($pageName) || ! is_numeric($page) || request()->query->has($pageName)) {
                continue;
            }

            $this->setPage(max(1, (int) $page), $pageName);
        }
    }

    public function dehydrateRemembersAdminPagination(): void
    {
        if (! request()->hasSession()) {
            return;
        }

        $pages = collect($this->paginators ?? [])
            ->filter(fn ($page, $pageName) => is_string($pageName) && is_numeric($page))
            ->map(fn ($page) => max(1, (int) $page))
            ->all();

        session()->put($this->adminPaginationSessionKey(), $pages);
    }

    private function adminPaginationSessionKey(): string
    {
        return 'admin.pagination.'.str_replace('\\', '.', static::class);
    }
}
