
# Client Class Documentation

The following parameters are configurable for the API Client:

| Parameter | Type | Description |
|  --- | --- | --- |
| environment | `Environment` | The API environment. <br> **Default: `Environment.SANDBOX`** |
| timeout | `int` | Timeout for API calls in seconds.<br>*Default*: `0` |
| enableRetries | `bool` | Whether to enable retries and backoff feature.<br>*Default*: `false` |
| numberOfRetries | `int` | The number of retries to make.<br>*Default*: `0` |
| retryInterval | `float` | The retry time interval between the endpoint calls.<br>*Default*: `1` |
| backOffFactor | `float` | Exponential backoff factor to increase interval between retries.<br>*Default*: `2` |
| maximumRetryWaitTime | `int` | The maximum wait time in seconds for overall retrying requests.<br>*Default*: `0` |
| retryOnTimeout | `bool` | Whether to retry on request timeout.<br>*Default*: `true` |
| httpStatusCodesToRetry | `array` | Http status codes to retry against.<br>*Default*: `408, 413, 429, 500, 502, 503, 504, 521, 522, 524` |
| httpMethodsToRetry | `array` | Http methods to retry against.<br>*Default*: `'GET', 'PUT'` |
| proxyConfiguration | [`ProxyConfigurationBuilder`](../doc/proxy-configuration-builder.md) | Represents the proxy configurations for API calls |
| userIdCredentials | [`UserIdCredentials`](auth/custom-header-signature.md) | The Credentials Setter for Custom Header Signature |
| userApiKeyCredentials | [`UserApiKeyCredentials`](auth/custom-header-signature-1.md) | The Credentials Setter for Custom Header Signature |
| developerIdCredentials | [`DeveloperIdCredentials`](auth/custom-header-signature-2.md) | The Credentials Setter for Custom Header Signature |
| accessTokenCredentials | [`AccessTokenCredentials`](auth/custom-header-signature-3.md) | The Credentials Setter for Custom Header Signature |

The API client can be initialized as follows:

```php
use FortisAPILib\Environment;
use FortisAPILib\Authentication\UserIdCredentialsBuilder;
use FortisAPILib\Authentication\UserApiKeyCredentialsBuilder;
use FortisAPILib\Authentication\DeveloperIdCredentialsBuilder;
use FortisAPILib\Authentication\AccessTokenCredentialsBuilder;
use FortisAPILib\FortisAPIClientBuilder;

$client = FortisAPIClientBuilder::init()
    ->userIdCredentials(
        UserIdCredentialsBuilder::init(
            'user-id'
        )
    )
    ->userApiKeyCredentials(
        UserApiKeyCredentialsBuilder::init(
            'user-api-key'
        )
    )
    ->developerIdCredentials(
        DeveloperIdCredentialsBuilder::init(
            'developer-id'
        )
    )
    ->accessTokenCredentials(
        AccessTokenCredentialsBuilder::init(
            'access-token'
        )
    )
    ->environment(Environment::SANDBOX)
    ->build();
```

## Fortis API Client

The gateway for the SDK. This class acts as a factory for the Controllers and also holds the configuration of the SDK.

## Controllers

| Name | Description |
|  --- | --- |
| getAsyncProcessingController() | Gets AsyncProcessingController |
| getBatchesController() | Gets BatchesController |
| getContactsController() | Gets ContactsController |
| getDeclinedRecurringTransactionsController() | Gets DeclinedRecurringTransactionsController |
| getDeviceTermsController() | Gets DeviceTermsController |
| getElementsController() | Gets ElementsController |
| getFullBoardingController() | Gets FullBoardingController |
| getLocationsController() | Gets LocationsController |
| getM3DSAuthenticationController() | Gets M3DSAuthenticationController |
| getM3DSTransactionsController() | Gets M3DSTransactionsController |
| getMerchantDepositsController() | Gets MerchantDepositsController |
| getOnBoardingController() | Gets OnBoardingController |
| getPaylinksController() | Gets PaylinksController |
| getPaymentCardReaderTokenController() | Gets PaymentCardReaderTokenController |
| getQuickInvoicesController() | Gets QuickInvoicesController |
| getRecurringController() | Gets RecurringController |
| getSignaturesController() | Gets SignaturesController |
| getTagsController() | Gets TagsController |
| getTerminalsController() | Gets TerminalsController |
| getTicketsController() | Gets TicketsController |
| getTokensController() | Gets TokensController |
| getTransactionACHRetriesController() | Gets TransactionACHRetriesController |
| getTransactionsACHController() | Gets TransactionsACHController |
| getTransactionsCashController() | Gets TransactionsCashController |
| getTransactionsCreditCardController() | Gets TransactionsCreditCardController |
| getTransactionsEBTCardController() | Gets TransactionsEBTCardController |
| getTransactionsReadController() | Gets TransactionsReadController |
| getLevel3DataController() | Gets Level3DataController |
| getTransactionsUpdatesController() | Gets TransactionsUpdatesController |
| getUserVerificationsController() | Gets UserVerificationsController |
| getUsersController() | Gets UsersController |
| getApplePayValidateMerchantController() | Gets ApplePayValidateMerchantController |
| getMerchantDetailsController() | Gets MerchantDetailsController |
| getWebhooksController() | Gets WebhooksController |

