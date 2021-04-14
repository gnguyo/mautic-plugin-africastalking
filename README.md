# Africastalking SMS API for Mautic v3

## Instalation
1. upload the contents in this repo to Mautic instalation `plugins/MauticAfricastalkingBundle`
2. Delete  cache `php app/console cache:clear`
3. Run `php app/console mautic:plugins:install`
4. Go to Plugins in Mautic's admin menu (/s/plugins)
5. Click on Africastalking, publish, and configure credentials 
6. Go to Mautic's Configuration (/s/config/edit), click on the Text Message Settings, then choose Africastalking as the default transport.

## Author

Gibson Nguyo