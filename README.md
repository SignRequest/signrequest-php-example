# signrequest-php-example
SignRequest php example

Example in php to use the SignRequest API: https://signrequest.com/api/v1/docs/

# Setup

`composer install`

# Run

```bash
export SR_API_TOKEN=your_api_token
php quickcreate.php from_signer_email@example.com to_signer_email@example.com
```

Now in the output find the `embed_url` and go there to sign the document. After signing / declining you will get redirected to google search as an example.

