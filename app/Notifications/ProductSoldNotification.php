<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductSoldNotification extends Notification
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

        $order = get_sold_order($this->data->number)->first();
        $order->products = get_order_products($this->data->number);

        return (new MailMessage)
                    ->subject('Your '.env('APP_NAME').' product sold! ('.$this->data->number.')')
                    ->line('Congratulations! Please ship the following in the order - ('.$this->data->number.').')
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
            'type' => 'sold',
            'order_number' => $this->data->number
        ];
    }
}
