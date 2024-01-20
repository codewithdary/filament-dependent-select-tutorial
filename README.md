## Make Select Field Depent On Another Select in FilamentPHP

In this tutorial, we will learn how to create dependent select fields in Filament, where the options in one select field depend on the value selected in another select field. In this example, the options in the `client_id` field will be dependent on the selected value in the `company_id` field.
•	Twitter: [@codewithdary](https://twitter.com/codewithdary) <br>
•	Instagram: [@codewithdary](https://www.instagram.com/codewithdary/) <br>
•	TikTok: [@codewithdary](https://tiktok.com/@codewithdary) <br>
•	Blog: [@codewithdary](https://blog.codewithdary.com) <br>
•	Patreon: [@codewithdary](https://www.patreon.com/user?u=30307830) <br>
<br>

## Prerequisites

Before getting started, make sure you have the following:

- Laravel and Filament installed on your machine
- Basic knowledge of Laravel and Filament concepts

### Migrations

Create the necessary database migrations for the `companies`, `clients`, and `products` tables, including the foreign key relationships.

```php
Schema::create('companies', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});

Schema::create('clients', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('company_id')->constrained('companies');
    $table->timestamps();
});

Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('company_id')->constrained('companies');
    $table->foreignId('client_id')->constrained('clients');
    $table->timestamps();
});
```

###  Models

Generate the corresponding models for the `companies`, `clients`, and `products` tables.

```php
php artisan make:model Company
php artisan make:model Client
php artisan make:model Product
```

Add the `$guarded` property and relationships for the models.


## Filament Resources

Create the Filament resources for the `Company`, `Client`, and `Product` models using the `php artisan make:filament-resource` command.

```php
php artisan make:filament-resource Company
php artisan make:filament-resource Client
php artisan make:filament-resource Product
```

## Define Form Fields

Define the form fields for each resource in their respective `Form` classes, including the dependent select fields.

```php
// CompanyResource.php
public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->maxValue(50)
                        ->required(),

                    Forms\Components\MarkdownEditor::make('description')
            ])->columnSpanFull()
        ]);
}

// ClientsRelationManager.php
public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->columnSpanFull()
                ->maxLength(255),

            Forms\Components\MarkdownEditor::make('description')
            ->columnSpanFull()
        ]);
}

// ProductResource.php
public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->maxValue(50)
                                ->required()
                                ->columnSpanFull(),

                            Forms\Components\Select::make('company_id')
                                ->label('Company')
                                ->options(Company::pluck('title', 'id'))
                                ->required()
                                ->reactive(),

                            Forms\Components\Select::make('client_id')
                                ->label('Client')
                                ->disabled(fn (Get $get) : bool => ! filled($get('company_id')))
                                ->options(fn(Get $get) => Client::where('company_id', (int) $get('company_id'))->pluck('name', 'id'))
                                ->required(),

                            Forms\Components\MarkdownEditor::make('description')
                                ->columnSpanFull()
                        ])->columns(2)
                ])->columnSpanFull()
        ]);
}

```

## Conclusion

In this tutorial, we learned how to create dependent select fields in Filament. We explored the steps involved in setting up the necessary database migrations, models, resources, and views, and implemented the logic to dynamically fetch the dependent options based on the selected values in other fields. By leveraging the power of Filament's form components and Laravel's Eloquent relationships, we can easily create intuitive and interactive forms in our Filament admin interface.

I hope you found this tutorial helpful. If you have any further questions or need assistance, please let me know.
