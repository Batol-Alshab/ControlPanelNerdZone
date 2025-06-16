<?php

namespace App\Filament\Student\Pages\Auth;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    function form(Form $form): Form
    {
        return
            $this->makeForm()
                ->schema([
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                    $this->getSectionIdFormComponent(),
                    $this->getSexFormComponent(),
                    $this->getCityFormComponent(),
                ])
                ->columns(2)
                ->statePath('data');

    }

    protected function getSectionIdFormComponent(): Component
    {
        return Select::make('section_id')
                    ->required()
                    ->label(__('messages.section.label'))
                    ->options(
                        Section::all()->pluck('name', 'id')
                    );
                    // ->relationship('section','name');
    }
    protected function getSexFormComponent(): Component
    {
        return Select::make('sex')
                    ->label(__('messages.sex'))
                    ->required()
                    ->options([
                        0 => __('messages.male') ,
                        1 => __('messages.female'),
                    ]);
    }
    protected function getCityFormComponent(): Component
    {
        return TextInput::make('city')
                    ->label(__('messages.city'))
                    ->required();
    }

    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $user->assignRole('student');
        return $user;
    }

}
