# SignRequest php example code

PHP example to use the SignRequest API: https://signrequest.com/api/v1/docs/

By default this code creates an `embed_url` where you can send a signer to. The regular SignRequest email does not go out in this case. 
For more information about this go here:

https://signrequest.com/api/v1/docs/#section/Additional-signing-methods/Embed-url 

# Setup

Install composer if you do not have it yet and install requirements. 

https://getcomposer.org/download/

`composer install`

# Run as cli

```bash
export SR_API_TOKEN=your_api_token
php quickcreate.php from_signer_email@example.com to_signer_email@example.com
```

Now in the output find the `embed_url` and go there to sign the document. After signing / declining you will get redirected to google search as an example.

# Run as server

```bash
export SR_API_TOKEN=your_api_token
php -S localhost:8888
```
And go to: http://localhost:8888/
