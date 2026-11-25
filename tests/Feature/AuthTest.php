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

    public function test_registration_rejects_non_upnvj_email(): void
    {
        Notification::fake();

        foreach (['budi@gmail.com', 'budi@upnvj.ac.id.evil.com', 'budi@mahasiswa.upnvj.ac.id'] as $email) {
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

    public function test_registration_accepts_upnvj_email_and_sends_verification(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Kenny Pandia',
            'nim' => '2510512027',
            'email' => 'Kenny.Pandia@UPNVJ.AC.ID',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'kenny.pandia@upnvj.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertSame('2510512027', $user->nim);
        $this->assertNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);

        Notification::assertSentTo($user, VerifyEmail::class);
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

    public function test_login_and_logout_with_upnvj_email(): void
    {
        $user = User::factory()->create([
            'email' => 'mahasiswa@upnvj.ac.id',
        ]);

        $this->post('/login', [
            'email' => 'salah@gmail.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => 'mahasiswa@upnvj.ac.id',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => 'mahasiswa@upnvj.ac.id',
            'password' => 'password',
        ])->assertRedirect(route('items.index'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
