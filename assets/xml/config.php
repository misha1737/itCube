<?php if(basename(__file__) == 'config.php') exit; ?>
<?xml version="1.0" encoding="utf-8"?>
<xml>
    <Addresses>
        <!-- Enter your e-mail address -->
        <address>mikekozlov2@gmail.com</address>
        <address on="subject" value="Support"></address>
        <address on="subject" value="Sales"></address>
        <address on="subject" value="Other"></address>
    </Addresses>
    <Config>
        <smtp>
        <!-- smtp gmail config -->
            <use>no</use>
            <auth>yes</auth>
            <secure>tls</secure>
            <host>smtp.example.com</host>
            <username>example@server.com</username>
            <password>123456</password>
            <port>25</port>
        </smtp>
        <charset>utf-8</charset>
    </Config>
</xml>
