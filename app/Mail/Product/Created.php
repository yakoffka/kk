<?php

namespace App\Mail\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


use Illuminate\Support\Facades\Log;

class Created extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product, $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Создан товар "' . $this->product->name . '"';

        // from, subject, view, attach
        return $this
            ->markdown('emails.product.created')
            ->from(config('mail.mail_info'))
            // // ->text('emails.orders.shipped_plain');
            // // второй способ передачи данных в шаблон:
            // ->with([
            //     'product' => $this->product,
            //     'user' => $this->user,
            //     'var' => 'jjhbjhbjhbj',
            // ])
            ->subject($subject);

    }
}
