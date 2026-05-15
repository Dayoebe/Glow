<?php

namespace Tests\Unit;

use App\Notifications\ContentApprovalUpdated;
use PHPUnit\Framework\TestCase;

class ContentApprovalUpdatedTest extends TestCase
{
    public function test_approval_update_uses_mail_and_database_by_default(): void
    {
        $notification = new ContentApprovalUpdated(
            'news article',
            'Morning Update',
            'approved'
        );

        $this->assertSame(['mail', 'database'], $notification->via(new class {
        }));
    }

    public function test_approval_update_can_disable_mail_channel(): void
    {
        $notification = new ContentApprovalUpdated(
            'news article',
            'Morning Update',
            'approved',
            sendMail: false
        );

        $this->assertSame(['database'], $notification->via(new class {
        }));
    }
}
