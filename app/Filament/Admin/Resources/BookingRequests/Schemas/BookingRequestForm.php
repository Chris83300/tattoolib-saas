<?php

namespace App\Filament\Admin\Resources\BookingRequests\Schemas;

use App\Enums\BookingRequestStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BookingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'id')
                    ->required(),
                TextInput::make('bookable_type')
                    ->required(),
                TextInput::make('bookable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tattoo_size')
                    ->required(),
                TextInput::make('body_zone')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('tattooer_notes')
                    ->columnSpanFull(),
                TextInput::make('estimated_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('estimated_budget')
                    ->numeric(),
                Select::make('preferred_timeframe')
                    ->options(['asap' => 'Asap', '3-4months' => '3 4months', '5-6months' => '5 6months', '6plus' => '6plus']),
                TextInput::make('preferred_days'),
                Textarea::make('date_notes')
                    ->columnSpanFull(),
                DatePicker::make('preferred_date'),
                Select::make('preferred_time_slot')
                    ->options([
            'morning' => 'Morning',
            'afternoon' => 'Afternoon',
            'evening' => 'Evening',
            'anytime' => 'Anytime',
        ]),
                Textarea::make('preferred_time_notes')
                    ->columnSpanFull(),
                TextInput::make('proposed_dates'),
                TextInput::make('client_selected_dates'),
                DateTimePicker::make('date_selection_deadline'),
                DateTimePicker::make('client_dates_selected_at'),
                DatePicker::make('confirmed_date'),
                TextInput::make('confirmed_period'),
                Textarea::make('tattooer_acceptance_message')
                    ->columnSpanFull(),
                TextInput::make('total_deposit_amount')
                    ->numeric(),
                TextInput::make('deposit_amount')
                    ->numeric(),
                TextInput::make('estimated_total_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('price_estimate_min')
                    ->numeric(),
                TextInput::make('price_estimate_max')
                    ->numeric(),
                TextInput::make('client_payment_deadline_days')
                    ->required()
                    ->numeric()
                    ->default(7),
                TextInput::make('deposit_deadline_hours')
                    ->required()
                    ->numeric()
                    ->default(72),
                TextInput::make('tattooer_design_deadline_days')
                    ->required()
                    ->numeric()
                    ->default(7),
                DateTimePicker::make('client_payment_deadline'),
                DateTimePicker::make('tattooer_design_deadline'),
                DateTimePicker::make('design_sent_at'),
                DateTimePicker::make('deposit_deadline'),
                Toggle::make('is_long_term_booking')
                    ->required(),
                DateTimePicker::make('design_preparation_starts_at'),
                Toggle::make('design_preparation_notified')
                    ->required(),
                TextInput::make('included_design_versions')
                    ->required()
                    ->numeric()
                    ->default(3),
                TextInput::make('included_designs')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('modifications_per_design')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('design_versions_used')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('designs_sent_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('design_modifications_tracker'),
                TextInput::make('current_design_modifications_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('stripe_payment_intent_id'),
                Select::make('status')
                    ->options(BookingRequestStatus::class)
                    ->required(),
                DateTimePicker::make('deposit_paid_at'),
                DateTimePicker::make('expired_at'),
                DateTimePicker::make('accepted_at'),
                TimePicker::make('scheduled_start_time'),
                TimePicker::make('scheduled_end_time'),
                TextInput::make('scheduled_duration_minutes')
                    ->numeric(),
                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('balance_amount')
                    ->numeric(),
                DateTimePicker::make('balance_paid_at'),
                TextInput::make('balance_payment_method'),
                TextInput::make('balance_stripe_session_id'),
                TextInput::make('refund_amount')
                    ->numeric(),
                TextInput::make('refund_percent')
                    ->numeric(),
                DateTimePicker::make('refund_processed_at'),
                Toggle::make('tattooer_missed_deadline')
                    ->required(),
                Toggle::make('client_missed_deadline')
                    ->required(),
                DateTimePicker::make('appointment_datetime'),
                TextInput::make('appointment_duration_minutes')
                    ->numeric(),
                TextInput::make('overage_decision'),
                TextInput::make('surcharge_amount')
                    ->numeric(),
                DateTimePicker::make('surcharge_paid_at'),
                Textarea::make('overage_reason')
                    ->columnSpanFull(),
                TextInput::make('cancelled_by'),
                Textarea::make('cancellation_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('cancelled_at'),
            ]);
    }
}
