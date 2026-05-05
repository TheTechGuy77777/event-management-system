<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $bank_name
 * @property string $account_number
 * @property string $account_name
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BankAccount whereUserId($value)
 */
	class BankAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $event_type
 * @property string|null $country
 * @property string|null $location
 * @property bool $is_virtual
 * @property string|null $virtual_link
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string $timezone
 * @property bool $is_recurring
 * @property string|null $recurrence_rule
 * @property \Illuminate\Support\Carbon|null $recurrence_end
 * @property string|null $cover_image
 * @property string $payment_model
 * @property numeric $commission_rate
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property string|null $instagram
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $website
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventLineup> $lineup
 * @property-read int|null $lineup_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereIsVirtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePaymentModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereRecurrenceEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereRecurrenceRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVirtualLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string $role
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereUpdatedAt($value)
 */
	class EventLineup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $buyer_name
 * @property string $buyer_email
 * @property string|null $buyer_phone
 * @property numeric $total_amount
 * @property numeric $platform_commission
 * @property numeric $manager_earnings
 * @property string|null $payment_reference
 * @property string|null $payment_gateway
 * @property string $payment_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBuyerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBuyerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBuyerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereManagerEarnings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePlatformCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $ticket_id
 * @property string $attendee_name
 * @property string $attendee_email
 * @property string $ticket_code
 * @property numeric $unit_price
 * @property bool $is_checked_in
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Ticket $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAttendeeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAttendeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsCheckedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTicketCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string $ticket_type
 * @property string $admission_type
 * @property int|null $group_size
 * @property numeric $price
 * @property int $quantity
 * @property int $quantity_sold
 * @property int $purchase_limit
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketPerk> $perks
 * @property-read int|null $perks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereAdmissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereGroupSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket wherePurchaseLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereQuantitySold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ticket_id
 * @property string $perk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ticket $ticket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk wherePerk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketPerk whereUpdatedAt($value)
 */
	class TicketPerk extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $organization_name
 * @property string|null $phone
 * @property string|null $profile_photo
 * @property bool $is_active
 * @property bool $is_banned
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BankAccount|null $bankAccount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

