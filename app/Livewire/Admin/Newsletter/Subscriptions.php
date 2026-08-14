<?php

namespace App\Livewire\Admin\Newsletter;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\NewsletterSubscription;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $subscription = NewsletterSubscription::findOrFail($id);
        $subscription->is_active = !$subscription->is_active;
        $subscription->unsubscribed_at = $subscription->is_active ? null : now();
        $subscription->save();

        session()->flash('success', 'Subscription status updated.');
    }

    public function deleteSubscription($id)
    {
        $subscription = NewsletterSubscription::find($id);
        if ($subscription) {
            $subscription->delete();
            session()->flash('success', 'Subscription deleted.');
        }
    }

    public function exportCsv()
    {
        $filename = 'newsletter-subscribers-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Name', 'Status', 'Subscribed At', 'Source']);

            NewsletterSubscription::query()
                ->orderBy('email')
                ->chunk(500, function ($subscriptions) use ($handle) {
                    foreach ($subscriptions as $subscription) {
                        fputcsv($handle, [
                            $subscription->email,
                            $subscription->name,
                            $subscription->is_active ? 'active' : 'inactive',
                            optional($subscription->subscribed_at)->format('Y-m-d H:i:s'),
                            $subscription->source,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getSubscriptionsProperty()
    {
        return NewsletterSubscription::query()
            ->when($this->search, function ($query) {
                $query->where('email', 'like', "%{$this->search}%")
                    ->orWhere('name', 'like', "%{$this->search}%");
            })
            ->orderByDesc('subscribed_at')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.newsletter.subscriptions', [
            'subscriptions' => $this->subscriptions,
        ])->layout('layouts.admin', ['header' => 'Newsletter Subscribers']);
    }
}
