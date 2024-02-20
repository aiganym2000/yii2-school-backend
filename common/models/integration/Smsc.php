<?php


//https://smsc.kz/api/http
namespace common\models\integration;

/**
 * Class Sms
 * @package common\models\sms
 */
class Smsc
{
    public $url_params;

    const TRANSLIT_OFF = 0; //не переводить в транслит
    const TRANSLIT_TRANSLIT = 1; //перевести в транслит в виде "translit"
    const TRANSLIT_MPAHC = 2; //перевести в транслит в виде "mpaHc/Ium"

    const TINYURL_OFF = 0; //оставить ссылки в тексте сообщения без изменений
    const TINYURL_ON = 1; //сократить ссылки

    const TZ_MOSCOW = '0'; //часовой пояс Москвы
    const TZ_SHYMKENT = '-3'; //часовой пояс Шымкент

    const FLASH_OFF = 0; //обычное сообщение
    const FLASH_ON = 1; //Flash сообщение

    const BIN_OFF = 0; //обычное сообщение
    const BIN_HTTP = 1; //бинарное сообщение
    const BIN_HEX = 2; //бинарное сообщение, представленное в виде шестнадцатеричной строки (hex)

    const PUSH_OFF = 0; //обычное сообщение
    const PUSH_ON = 1; //wap-push сообщение

    const HLR_OFF = 0; //обычное сообщение
    const HLR_ON = 1; //HLR-запрос

    const PING_OFF = 0; //обычное сообщение
    const PING_ON = 1; //ping-sms

    const MMS_OFF = 0; //обычное сообщение
    const MMS_ON = 1; //MMS-сообщение

    const MAIL_OFF = 0; //обычное сообщение
    const MAIL_ON = 1; //e-mail сообщение

    const SOC_OFF = 0; //обычное сообщение
    const SOC_ON = 1; //soc-сообщение

    const CALL_OFF = 0; //обычное сообщение
    const CALL_ON = 1; //голосовое сообщение

    const VOICE_M = 'm'; //мужской голос
    const VOICE_M2 = 'm2'; //мужской альтернативный голос
    const VOICE_W = 'w'; //женский голос
    const VOICE_W2 = 'w2'; //женский альтернативный голос

    const CHARSET_WINDOWS1251 = 'windows-1251'; //кодировка windows-1251
    const CHARSET_UTF8 = 'utf8'; //кодировка utf8
    const CHARSET_KOI8R = 'koi8_r'; //кодировка koi8_r

    const COST_OFF = 0; //обычная отправка
    const COST_ON_WITHOUT_SEND = 1; //получить стоимость рассылки без реальной отправки
    const COST_ON = 2; //обычная отправка, но добавить в ответ стоимость выполненной рассылки
    const COST_ON_NEW_BALANCE = 3; //обычная отправка, но добавить в ответ стоимость и новый баланс Клиента

    const FMT_STRING = 0; //в виде строки (OK - 1 SMS, ID - 1234)
    const FMT_INT = 1; //вернуть ответ в виде чисел через запятую
    const FMT_XML = 2; //ответ в xml формате
    const FMT_JSON = 3; //ответ в json формате

    const ERR_OFF = 0; //обычное сообщение
    const ERR_ON = 1; // в ответ добавляется список ошибочных номеров телефонов с соответствующими статусами

    const OP_OFF = 0; //не добавлять список
    const OP_ON = 1; //в ответ добавляется список номеров телефонов с статусами, mcc и mnc, стоимостью, и кодами ошибок

    /**
     * @return array|string
     */
    public function getPhones()
    {
        return $this->url_params['phones'];
    }

    /**
     * Номер или список номеров, на которые отправляется сообщение. Номера могут передаваться без знака "+".
     * Для e-mail сообщения передается список e-mail адресов получателей.
     * @param array|string $phones
     * @return $this
     */
    public function setPhones($phones)
    {
        if (is_array($phones)) {
            $string = implode(',', $phones);
        } else {
            $string = $phones;
        }
        $this->url_params['phones'] = $string;
        return $this;
    }

    /**
     * @return string
     */
    public function getMes()
    {
        return $this->url_params['mes'];
    }

    /**
     * Текст отправляемого сообщения. Максимальный размер – 1000 символов.
     * Сообщение при необходимости будет разбито на несколько SMS, отправленных абоненту и оплаченных по отдельности.
     * @param string $mes
     * @param string $comment
     * @return $this
     */
    public function setMes($mes, $comment = null)
    {
        if ($comment) {
            $string = $mes . '\n~~~\n' . $comment;
        } else {
            $string = $mes;
        }
        $this->url_params['mes'] = $string;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->url_params['id'];
    }

    /**
     * Идентификатор сообщения. Если не указывать, то будет назначен автоматически. Не обязательно уникален.
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->url_params['id'] = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->url_params['sender'];
    }

    /**
     * Имя отправителя, отображаемое в телефоне получателя.
     * Разрешены английские буквы, цифры, пробел и некоторые символы. Длина – 11 символов или 15 цифр.
     * @param string $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->url_params['sender'] = $sender;
        return $this;
    }

    /**
     * @return int
     */
    public function getTranslit()
    {
        return $this->url_params['translit'];
    }

    /**
     * Признак того, что сообщение необходимо перевести в транслит. Используйте константы.
     * @param int $translit
     * @return $this
     */
    public function setTranslit($translit)
    {
        $this->url_params['translit'] = $translit;
        return $this;
    }

    /**
     * @return int
     */
    public function getTinyurl()
    {
        return $this->url_params['tinyurl'];
    }

    /**
     * Автоматически сокращать ссылки в сообщениях.
     * Позволяет заменять ссылки в тексте сообщения на короткие для сокращения длины,
     * а также для отслеживания количества переходов. Используйте константы.
     * @param int $tinyurl
     * @return $this
     */
    public function setTinyurl($tinyurl)
    {
        $this->url_params['tinyurl'] = $tinyurl;
        return $this;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->url_params['time'];
    }

    /**
     * Время отправки SMS-сообщения абоненту.
     * Форматы:
     * 1) DDMMYYhhmm или DD.MM.YY hh:mm.
     * 2) h1-h2. Задает диапазон времени в часах.
     * 3) 0ts, где ts – timestamp, время в секундах, прошедшее с 1 января 1970 года.
     * 4) +m. Задает относительное смещение времени от текущего в минутах.
     * Если time = 0 или указано уже прошедшее время, то сообщение будет отправлено немедленно.
     * @param string $time
     * @return $this
     */
    public function setTime($time)
    {
        $this->url_params['time'] = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getTz()
    {
        return $this->url_params['tz'];
    }

    /**
     * Часовой пояс, в котором задается параметр time.
     * Указывается относительно московского времени. Можете использовать константы.
     * @param string $tz
     * @return $this
     */
    public function setTz($tz)
    {
        $this->url_params['tz'] = $tz;
        return $this;
    }

    /**
     * @return int
     */
    public function getPeriod()
    {
        return $this->url_params['period'];
    }

    /**
     * @return int
     */
    public function getFreq()
    {
        return $this->url_params['freq'];
    }

    /**
     * Промежуток времени, в течение которого необходимо отправить рассылку.
     * Представляет собой число в диапазоне от 0.1 до 720 часов.
     * Данный параметр позволяет растянуть рассылку во времени для постепенного получения SMS-сообщений абонентами.
     * @param int $period
     * Интервал или частота, с которой нужно отправлять SMS-рассылку на очередную группу номеров.
     * Задается в промежутке от 1 до 1440 минут.
     * @param int $freq
     * @return $this
     */
    public function setPeriodAndFreq($period, $freq)
    {
        $this->url_params['period'] = $period;
        $this->url_params['freq'] = $freq;
        return $this;
    }

    /**
     * @return int
     */
    public function getFlash()
    {
        return $this->url_params['flash'];
    }

    /**
     * Признак Flash сообщения, отображаемого сразу на экране телефона. Используйте константы.
     * @param int $flash
     * @return $this
     */
    public function setFlash($flash)
    {
        $this->url_params['flash'] = $flash;
        return $this;
    }

    /**
     * @return int
     */
    public function getBin()
    {
        return $this->url_params['bin'];
    }

    /**
     * Признак бинарного сообщения. Используйте константы.
     * @param int $bin
     * @param int $pid
     * @param int $dcs
     * @return $this
     */
    public function setBin($bin, $pid = null, $dcs = null)
    {
        $this->url_params['bin'] = $bin;
        if ($pid && $dcs) {
            $this->url_params['bin'] .= '\n~~~\npid: ' . $pid . ', dcs: ' . $dcs;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getPush()
    {
        return $this->url_params['push'];
    }

    /**
     * Признак wap-push сообщения, с помощью которого можно отправить интернет-ссылку на телефон. Используйте константы.
     * @param int $push
     * @return $this
     */
    public function setPush($push)
    {
        $this->url_params['push'] = $push;
        return $this;
    }

    /**
     * @return int
     */
    public function getHlr()
    {
        return $this->url_params['hlr'];
    }

    /**
     * Признак HLR-запроса для получения информации о номере из базы оператора без отправки реального SMS.
     * Используйте константы.
     * @param int $hlr
     * @return $this
     */
    public function setHlr($hlr)
    {
        $this->url_params['hlr'] = $hlr;
        return $this;
    }

    /**
     * @return int
     */
    public function getPing()
    {
        return $this->url_params['ping'];
    }

    /**
     * Признак специального SMS,
     * не отображаемого в телефоне, для проверки номеров на доступность в реальном времени по статусу доставки.
     * Используйте константы.
     * @param int $ping
     * @return $this
     */
    public function setPing($ping)
    {
        $this->url_params[] = $ping;
        return $this;
    }

    /**
     * @return int
     */
    public function getMms()
    {
        return $this->url_params['mms'];
    }

    /**
     * Признак MMS-сообщения, с помощью которого можно передавать текст, изображения, музыку и видео.
     * Используйте константы.
     * @param int $mms
     * @return $this
     */
    public function setMms($mms)
    {
        $this->url_params['mms'] = $mms;
        return $this;
    }

    /**
     * @return int
     */
    public function getMail()
    {
        return $this->url_params['mail'];
    }

    /**
     * Признак e-mail сообщения. Используйте константы.
     * @param int $mail
     * @return $this
     */
    public function setMail($mail)
    {
        $this->url_params['mail'] = $mail;
        return $this;
    }

    /**
     * @return int
     */
    public function getSoc()
    {
        return $this->url_params['soc'];
    }

    /**
     * Признак soc-сообщения,
     * отправляемого пользователям социальных сетей "Одноклассники", "ВКонтакте" или пользователям "Mail.Ru Агент".
     * Используйте константы.
     * @param int $soc
     * @return $this
     */
    public function setSoc($soc)
    {
        $this->url_params['soc'] = $soc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViber()
    {
        return $this->url_params['viber'];
    }

    /**
     * Признак viber-сообщения, отправляемого пользователям мессенджера Viber.
     * @param mixed $viber
     * @return $this
     */
    public function setViber($viber)
    {
        $this->url_params['viber'] = $viber;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileurl()
    {
        return $this->url_params['fileurl'];
    }

    /**
     * Полный http-адрес файла для загрузки и передачи в сообщении.
     * @param string $fileurl
     * @return $this
     */
    public function setFileurl($fileurl)
    {
        $this->url_params['fileurl'] = $fileurl;
        return $this;
    }

    /**
     * @return int
     */
    public function getCall()
    {
        return $this->url_params['call'];
    }

    /**
     * Признак голосового сообщения. Используйте константы.
     * @param int $call
     * @return $this
     */
    public function setCall($call)
    {
        $this->url_params['call'] = $call;
        return $this;
    }

    /**
     * @return string
     */
    public function getVoice()
    {
        return $this->url_params['voice'];
    }

    /**
     * Голос, используемый для озвучивания текста (только для голосовых сообщений). Используйте константы.
     * @param string $voice
     * @return $this
     */
    public function setVoice($voice)
    {
        $this->url_params['voice'] = $voice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->url_params['param'];
    }

    /**
     * Время ожидания поднятия трубки абонентом после начала звонка в секундах.
     * Если в течение указанного времени абонент не поднимет трубку, то звонок уйдет на повтор с ошибкой "абонент занят".
     * Интервал от 10 до 35.
     * @param int $w
     * Интервал повтора, то есть промежуток времени, по истечении которого произойдет повторный звонок (в секундах).
     * Интервал от 10 до 3600.
     * @param int $i
     * Общее количество попыток дозвона. Интервал от 1 до 9.
     * @param int $n
     * @return $this
     */
    public function setParam($w, $i, $n)
    {
        $this->url_params['param'] = $w . ',' . $i . ',' . $n;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubj()
    {
        return $this->url_params['subj'];
    }

    /**
     * Тема MMS или e-mail сообщения. При отправке e-mail указание темы, текста и адреса отправителя обязательно.
     * Для MMS обязательным является указание темы или текста.
     * @param string $subj
     * @return $this
     */
    public function setSubj($subj)
    {
        $this->url_params['subj'] = $subj;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->url_params['charset'];
    }

    /**
     * Кодировка переданного сообщения, если используется отличная от кодировки по умолчанию windows-1251.
     * Используйте константы.
     * @param string $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->url_params['charset'] = $charset;
        return $this;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->url_params['cost'];
    }

    /**
     * Признак необходимости получения стоимости рассылки. Используйте константы.
     * @param int $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->url_params['cost'] = $cost;
        return $this;
    }

    /**
     * @return int
     */
    public function getFmt()
    {
        return $this->url_params['fmt'];
    }

    /**
     * Формат ответа сервера об успешной отправке. Используйте константы.
     * @param int $fmt
     * @return $this
     */
    public function setFmt($fmt)
    {
        $this->url_params['fmt'] = $fmt;
        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->url_params['list'];
    }

    /**
     * Список номеров телефонов и соответствующих им сообщений,
     * разделенных двоеточием или точкой с запятой и представленный в виде:
     * phones1:mes1, phones2:mes2
     * @param array $list
     * В случае невозможности корректировки текста мультистрокового сообщения возможно включение специального режима
     * для передачи такого типа сообщений.
     * @param int $nl
     * @return $this
     */
    public function setList($list, $nl = null)
    {
        $arr = [];
        foreach ($list as $key => $value) {
            $arr[] = $key . ':' . $value;
        }
        $this->url_params['list'] = implode(',', $arr);
        if ($nl) {
            $this->url_params['nl'] = $nl;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getValid()
    {
        return $this->url_params['valid'];
    }

    /**
     * Срок "жизни" SMS-сообщения.
     * Определяет время, в течение которого оператор будет пытаться доставить сообщение абоненту.
     * Диапазон от 1 до 24 часов. Также возможно передавать время в формате чч:мм в диапазоне от 00:01 до 24:00.
     * @param string $valid
     * @return $this
     */
    public function setValid($valid)
    {
        $this->url_params['valid'] = $valid;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxsms()
    {
        return $this->url_params['maxsms'];
    }

    /**
     * Максимальное количество SMS, на которые может разбиться длинное сообщение.
     * Слишком длинные сообщения будут обрезаться так, чтобы не переполнить количество SMS, требуемых для их передачи.
     * @param int $maxsms
     * @return $this
     */
    public function setMaxsms($maxsms)
    {
        $this->url_params['maxsms'] = $maxsms;
        return $this;
    }

    /**
     * @return string
     */
    public function getImgcode()
    {
        return $this->url_params['imgcode'];
    }

    /**
     * @return string
     */
    public function getUserip()
    {
        return $this->url_params['userip'];
    }

    /**
     * Значение буквенно-цифрового кода, введенного с "captcha" при использовании антиспам проверки.
     * @param string $imgcode
     * Значение IP-адреса, для которого будет действовать лимит на максимальное количество сообщений в сутки.
     * @param string $userip
     * @return $this
     */
    public function setImgcodeAndUserip($imgcode, $userip)
    {
        $this->url_params['imgcode'] = $imgcode;
        $this->url_params['userip'] = $userip;
        return $this;
    }

    /**
     * @return int
     */
    public function getErr()
    {
        return $this->url_params['err'];
    }

    /**
     * Признак необходимости добавления в ответ сервера списка ошибочных номеров. Используйте константы.
     * @param int $err
     * @return $this
     */
    public function setErr($err)
    {
        $this->url_params['err'] = $err;
        return $this;
    }

    /**
     * @return int
     */
    public function getOp()
    {
        return $this->url_params['op'];
    }

    /**
     * Признак необходимости добавления в ответ сервера информации по каждому номеру. Используйте константы.
     * @param int $op
     * @return $this
     */
    public function setOp($op)
    {
        $this->url_params['op'] = $op;
        return $this;
    }

    /**
     * @return int
     */
    public function getPp()
    {
        return $this->url_params['pp'];
    }

    /**
     * Осуществляет привязку Клиента в качестве реферала к определенному ID партнера для текущего запроса.
     * @param int $pp
     * @return $this
     */
    public function setPp($pp)
    {
        $this->url_params['pp'] = $pp;
        return $this;
    }

    /**
     * @return bool|mixed|string
     */
    public function send()
    {
        $params = \Yii::$app->params['sms'];
        $url = $params['url'] . '?login=' . $params['login'] . '&psw=' . $params['password'];
        foreach ($this->url_params as $key => $value) {
            $url .= '&' . $key . '=' . urlencode($value);
        }
        $json = file_get_contents($url);
        if($this->url_params['fmt'] == self::FMT_JSON){
            $json = json_decode($json);
        }
        return $json;
    }
}