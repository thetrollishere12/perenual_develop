<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductShippedNotification extends Notification
{
    use Queueable;
    private $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $order = get_order($this->data->number)->first();
        $order->products = get_order_products($this->data->number);

        return (new MailMessage)
                    ->subject('Your '.env('APP_NAME').' order has been shipped! ('.$this->data->number.')')
                    ->line('Your order - ('.$this->data->number.') has been shipped out.')
                    // ->action('Notification Action', url('/'))
                    ->markdown('vendor.notifications.order-basic',[
                        'order' => $order
                    ]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'shipped',
            'order_number' => $this->data->number
        ];
    }
}
