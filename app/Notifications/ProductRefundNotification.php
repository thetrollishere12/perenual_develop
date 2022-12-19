<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRefundNotification extends Notification
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
        if ($this->data->refunded['amount']) {
            $total = $this->data->customer_currency." ".$this->data->refunded['customer_amount'];
        }
        
        return (new MailMessage)
        ->subject('You received '.$total.' refund from your '.env('APP_NAME').' order')
        ->line('You received a '.$total.' refund from your order - ('.$this->data->number.').')
        ->markdown('vendor.notifications.product-refund',[
            "order" => $this->data
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
            'type' => 'refund',
            'order_number' => $this->data->number
        ];
    }
}
