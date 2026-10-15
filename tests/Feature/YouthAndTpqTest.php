<?php

use App\Models\Role;
use App\Models\Santri;
use App\Models\TpqClass;
use App\Models\User;

function makeUser(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucfirst($roleName)]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('sekretaris can manage youth members', function () {
    $sekretaris = makeUser('sekretaris');

    $response = $this->actingAs($sekretaris)->post(route('youth-members.store'), [
        'name' => 'Ahmad Fauzi',
        'division' => 'Humas & Media',
        'position' => 'Koordinator',
        'phone' => '081234567890',
        'status' => 'aktif',
    ]);

    $response->assertRedirect(route('youth-members.index'));
    $this->assertDatabaseHas('youth_members', [
        'name' => 'Ahmad Fauzi',
        'division' => 'Humas & Media',
        'position' => 'Koordinator',
        'status' => 'aktif',
    ]);
});

test('sekretaris can manage youth programs', function () {
    $sekretaris = makeUser('sekretaris');

    $response = $this->actingAs($sekretaris)->post(route('youth-programs.store'), [
        'name' => 'Latihan Dasar Kepemimpinan Remaja Masjid',
        'division' => 'Kaderisasi',
        'description' => 'Membangun karakter pemuda masjid yang berakhlak mulia.',
        'start_date' => now()->addWeeks(2)->toDateString(),
        'budget' => 2500000,
        'status' => 'rencana',
    ]);

    $response->assertRedirect(route('youth-programs.index'));
    $this->assertDatabaseHas('youth_programs', [
        'name' => 'Latihan Dasar Kepemimpinan Remaja Masjid',
        'status' => 'rencana',
    ]);
});

test('sekretaris can update santri with hafalan and bacaan progress', function () {
    $sekretaris = makeUser('sekretaris');

    $class = TpqClass::create([
        'name' => 'Kelas Wustha',
        'level' => 'Jilid 4',
        'is_active' => true,
    ]);

    $santri = Santri::create([
        'registration_number' => 'SAN-20261014-0001',
        'tpq_class_id' => $class->id,
        'name' => 'Muhammad Bilal',
        'birth_date' => '2016-05-10',
        'gender' => 'Laki-laki',
        'parent_name' => 'Bapak Usman',
        'parent_phone' => '081299887766',
        'address' => 'Komplek Masjid Blok C No 5',
        'status' => 'active',
    ]);

    $response = $this->actingAs($sekretaris)->put(route('santris.update', $santri), [
        'tpq_class_id' => $class->id,
        'name' => 'Muhammad Bilal',
        'birth_date' => '2016-05-10',
        'gender' => 'Laki-laki',
        'parent_name' => 'Bapak Usman',
        'parent_phone' => '081299887766',
        'address' => 'Komplek Masjid Blok C No 5',
        'status' => 'active',
        'progress_hafalan' => 'Juz 30 (An-Naba s/d Al-Infitar)',
        'progress_bacaan' => 'Al-Quran Juz 2 Hal 35',
        'learning_notes' => 'Makharijul huruf sangat fasih, perlu pembiasaan mad wajib muttashil.',
    ]);

    $response->assertRedirect(route('santris.index'));
    $this->assertDatabaseHas('santris', [
        'id' => $santri->id,
        'progress_hafalan' => 'Juz 30 (An-Naba s/d Al-Infitar)',
        'progress_bacaan' => 'Al-Quran Juz 2 Hal 35',
    ]);
});

test('marbot can record santri attendance', function () {
    $marbot = makeUser('marbot');

    $class = TpqClass::create([
        'name' => 'Kelas Ula',
        'level' => 'Iqro 1',
        'is_active' => true,
    ]);

    $santri = Santri::create([
        'registration_number' => 'SAN-20261014-0002',
        'tpq_class_id' => $class->id,
        'name' => 'Aisyah Putri',
        'birth_date' => '2018-01-01',
        'gender' => 'Perempuan',
        'parent_name' => 'Ibu Fatimah',
        'status' => 'active',
    ]);

    $response = $this->actingAs($marbot)->post(route('santri-attendances.store'), [
        'attendance_date' => today()->toDateString(),
        'class_id' => $class->id,
        'attendances' => [
            [
                'santri_id' => $santri->id,
                'status' => 'present',
                'notes' => 'Tepat waktu',
            ],
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('santri_attendances', [
        'santri_id' => $santri->id,
        'status' => 'present',
    ]);
});
