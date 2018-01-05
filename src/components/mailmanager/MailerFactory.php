<?php

namespace infoservio\fastsendnote\components\fastsendnote;

use craft\events\RegisterComponentTypesEvent;
use craft\helpers\Component;
use infoservio\fastsendnote\components\fastsendnote\transports\BaseTransport;
use infoservio\fastsendnote\components\fastsendnote\transports\Mailgun;
use infoservio\fastsendnote\components\fastsendnote\transports\Gmail;
use infoservio\fastsendnote\components\fastsendnote\transports\Postal;
use yii\base\Event;

class MailerFactory
{
    // Constants
    // =========================================================================
    const GMAIL = 1;
    const MAILGUN = 2;
    const POSTAL = 3;


    const TRANSPORT_TYPES_CODES = [
        Gmail::class => ['id' => 1, 'name' => 'Gmail Mailer'],
        Mailgun::class => ['id' => 2, 'name' => 'Mailgun Mailer'],
        Postal::class => ['id' => 3, 'name' => 'Postal Mailer']
    ];

    const TRANSPORT_TYPES = [
        Gmail::class,
        Mailgun::class,
        Postal::class
    ];
    /**
     * @event RegisterComponentTypesEvent The event that is triggered when registering mailer transport adapter types.
     */
    const EVENT_REGISTER_MAILER_TRANSPORT_TYPES = 'registerMailerTransportTypes';

    // Static
    // =========================================================================

    /**
     * Returns all available mailer transport adapter classes.
     *
     * @return string[]
     */
    public static function allMailerTransportTypes(): array
    {
        $event = new RegisterComponentTypesEvent([
            'types' => self::TRANSPORT_TYPES
        ]);
        Event::trigger(static::class, self::EVENT_REGISTER_MAILER_TRANSPORT_TYPES, $event);

        return $event->types;
    }


    /**
     * @param string $type
     * @param array|null $settings
     * @return BaseTransport
     */
    public static function createTransport(string $type, array $settings = null): BaseTransport
    {
        /** @var BaseTransport $adapter */
        $adapter = Component::createComponent([
            'type' => $type,
            'settings' => $settings,
        ], BaseTransport::class);

        return $adapter;
    }
}