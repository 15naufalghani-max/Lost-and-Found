<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_pages_render(): void
    {
        $this->get('/register')->assertOk();
        $this->get('/login')->assertOk();
    }

    public function test_registration_rejects_non_campus_and_plain_upnvj_emails(): void
    {
        Notification::fake();

        $invalidEmails = [
            'budi@gmail.com',
            'budi@upnvj.ac.id',
            'budi@staff.upnvj.ac.id',
            'budi@mahasiswa.upnvj.ac.id.evil.com',
        ];

        foreach ($invalidEmails as $email) {
            $response = $this->post('/register', [
                'name' => 'Budi Santoso',
                'nim' => '2510512099',
                'email' => $email,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertSessionHasErrors('email');
        }

        $this->assertDatabaseCount('users', 0);
        Notification::assertNothingSent();
    }

    public function test_registration_accepts_mahasiswa_and_dosen_emails_and_sends_verification(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Kenny Pandia',
            'nim' => '2510512027',
            'email' => '2510512027@MAHASISWA.UPNVJ.AC.ID',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $student = User::where('email', '2510512027@mahasiswa.upnvj.ac.id')->first();
        $this->assertNotNull($student);
        $this->assertSame('2510512027', $student->nim);
        $this->assertNull($student->email_verified_at);
        $this->assertAuthenticatedAs($student);
        Notification::assertSentTo($student, VerifyEmail::class);

        $this->post('/logout');

        $dosenResponse = $this->post('/register', [
            'name' => 'Dr. Andi Wijaya',
            'nim' => '',
            'email' => 'andi.wijaya@dosen.upnvj.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $dosenResponse->assertRedirect(route('verification.notice'));
        $dosen = User::where('email', 'andi.wijaya@dosen.upnvj.ac.id')->first();
        $this->assertNotNull($dosen);
        $this->assertNull($dosen->nim);
        Notification::assertSentTo($dosen, VerifyEmail::class);
    }

    public function test_unverified_user_cannot_create_item_until_email_verified(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('items.create'))
            ->assertRedirect(route('verification.notice'));

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('items.index'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        $this->actingAs($user->fresh())
            ->get(route('items.create'))
            ->assertOk();
    }

    public function test_login_and_logout_with_mahasiswa_and_dosen_emails(): void
    {
        $student = User::factory()->create([
            'email' => '2510512027@mahasiswa.upnvj.ac.id',
        ]);
        $dosen = User::factory()->create([
            'nim' => null,
            'email' => 'dosen.fik@dosen.upnvj.ac.id',
        ]);

        $this->post('/login', [
            'email' => 'salah@upnvj.ac.id',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => '2510512027@mahasiswa.upnvj.ac.id',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => '2510512027@mahasiswa.upnvj.ac.id',
            'password' => 'password',
        ])->assertRedirect(route('items.index'));

        $this->assertAuthenticatedAs($student);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'dosen.fik@dosen.upnvj.ac.id',
            'password' => 'password',
        ])->assertRedirect(route('items.index'));

        $this->assertAuthenticatedAs($dosen);
    }
}
