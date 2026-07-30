<?php
namespace App\Actions\Fortify;
use Illuminate\Contracts\Auth\MustVerifyEmail;use Illuminate\Support\Facades\Validator;use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
class UpdateUserProfileInformation implements UpdatesUserProfileInformation { public function update($user, array $input): void {Validator::make($input,['name'=>['required','string','max:255'],'email'=>['required','email','max:255','unique:users,email,'.$user->id]])->validate(); if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {$user->forceFill(['name'=>$input['name'],'email'=>$input['email'],'email_verified_at'=>null])->save();$user->sendEmailVerificationNotification();return;} $user->forceFill(['name'=>$input['name'],'email'=>$input['email']])->save();}}
