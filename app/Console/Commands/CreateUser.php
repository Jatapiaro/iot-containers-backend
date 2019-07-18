<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Services\ParticleService;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'containers:create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new user';

    /**
     * Particle service
     *
     * @var App\Services\ParticleService
     */
    private $ps;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ParticleService $ps)
    {
        parent::__construct();
        $this->ps = $ps;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Ingresa el correo del usuario');
        while( !$this->validateEmail($email) ) {
            $this->error("Introduce un email válido");
            $email = $this->ask('Ingresa el correo del usuario');
        }
        $name = $this->ask('Ingresa el nombre del usuario');
        while( !$this->validateRequired($name) ) {
            $this->error("Por favor, introduce un nombre");
            $name = $this->ask('Ingresa el nombre del usuario');
        }
        $password = $this->secret('Ingresa el password para el usuario');
        while( !$this->validateRequired($password) ) {
            $this->error("Por favor, introduce un password");
            $password = $this->secret('Ingresa el password para el usuario');
        }
        $confirmation = $name . ' con correo ' . $email;
        if ($this->confirm($confirmation . ' ¿es correcto?')) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);
            $this->ps->mirror($user->email);
            $this->info($confirmation . ' ha sido creado');
        } else {
            $this->error("Adición de usuario cancelada");
        }
    }

    private function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    private function validateRequired($value){
        if (is_null($value)) {
            return false;
        } else if (is_string($value) && trim($value) === '') {
            return false;
        }
        return true;
    }

}
