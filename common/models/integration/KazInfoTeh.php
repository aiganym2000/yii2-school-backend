<?php

namespace common\models\integration;
//http://docs.kazinfoteh.kz/protocols/http/outbox/
class KazInfoTeh
{
    const TYPE_TEXT = 'SMS:TEXT';//текстовое SMS сообщение
    const TYPE_FLASH = 'SMS:TEXT:GSM7BIT:CLASS0';//текстовое SMS сообщение, FLASH SMS
    const TYPE_MOBILE = 'SMS:TEXT:GSM7BIT:CLASS1';//текстовое SMS сообщение сохраняется в память мобильного устройства
    const TYPE_SIM = 'SMS:TEXT:GSM7BIT:CLASS2';//текстовое SMS сообщение сохраняется на SIM карту
    const TYPE_BINARY = 'SMS:BINARY';//бинарное SMS сообщение
    const TYPE_BINARY_XML = 'SMS:BINARY:XML';//бинарное SMS сообщение, содержащее PID, DCS и UDH поля в SMS PDU
    const TYPE_WAPPUSH = 'SMS:WAPPUSH';//WAP PUSH сообщение, содержащее ссылку на мелодию, картинку, видео, интернет страницу и т.п.
    const TYPE_BOOKMARK = 'SMS:WAPPUSH:BOOKMARK';//закладка на интернет страницу
    const TYPE_VCARD = 'SMS:VCARD';//виртуальная визитная карточка
    const TYPE_VCALENDAR = 'SMS:VCALENDAR';//напоминание в календарь
    const TYPE_VOICEMAIL = 'SMS:INDICATION:VOICEMAIL';//индикатор о голосовой почте
    const TYPE_EMAIL = 'SMS:INDICATION:EMAIL';//индикатор об электронной почте
    const TYPE_FAX = 'SMS:INDICATION:FAX';//индикатор о факсе
    const TYPE_VIDEO = 'SMS:INDICATION:VIDEO';//индикатор о виде
    const TYPE_PICTUREMESSAGE = 'SMS:BINARY:PICTUREMESSAGE';//бинарная картинка

    /**
     * Отправка сообщений
     * @param int $recipient номер получателя в формате 77aabbbccdd
     * @param string $messagetype тип SMS
     * @param string $messagedata текст сообщения
     * @param string $originator заголовок отправителя
     * @param null|string $sendondate время отправки SMS (YYYY-MM-DD HH:mm:ss)
     * @param null|string $responseformat формат возвращаемого ответа
     * @param null|string $continueurl URL, который автоматически добавится к ответу после отправки SMS
     * @param null|string $redirecturl URL, на который на который произойтет перенаправление после отправки SMS
     * @param null|string $reporturl URL, который будет вызван при изменении статуса SMS (доставлено/ не доставлено)
     * @return array
     */
    public static function sendMessage($recipient, $messagedata, $messagetype = self::TYPE_TEXT, $originator = 'WeShop', $sendondate = null,
                                       $responseformat = null, $continueurl = null, $redirecturl = null,
                                       $reporturl = null)
    {
        $params = [
            'recipient' => str_replace('+', '', $recipient),
            'messagetype' => $messagetype,
            'messagedata' => $messagedata,
            'originator' => $originator,
            'sendondate' => $sendondate,
            'responseformat' => $responseformat,
            'continueurl' => $continueurl,
            'redirecturl' => $redirecturl,
            'reporturl' => $reporturl,
        ];
        return self::query($params, 'sendmessage');
    }

    /**
     * Построитель запросов
     * @param $params
     * @param $address
     * @return array
     */
    private static function query($params, $address)
    {
        $sms = \Yii::$app->params['sms'];
        $queryParams = 'action=' . $address . '&username=' . $sms['login'] . '&password=' . $sms['password'];
        foreach ($params as $key => $value) {
            if ($value != null) {
                $queryParams = $queryParams . '&' . $key . '=' . urlencode($value);
            }
        }
        $url = $sms['url'] . '?' . $queryParams;

        $response = file_get_contents($url);

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}