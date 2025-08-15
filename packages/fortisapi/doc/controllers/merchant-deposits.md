# Merchant Deposits

```php
$merchantDepositsController = $client->getMerchantDepositsController();
```

## Class Name

`MerchantDepositsController`

## Methods

* [View Single Merchant Deposit](../../doc/controllers/merchant-deposits.md#view-single-merchant-deposit)
* [List All Merchant Deposits](../../doc/controllers/merchant-deposits.md#list-all-merchant-deposits)


# View Single Merchant Deposit

```php
function viewSingleMerchantDeposit(
    string $depositId,
    ?Page $page = null,
    ?array $order = null,
    ?array $filterBy = null,
    ?array $expand = null,
    ?string $format = null,
    ?string $typeahead = null,
    ?array $fields = null,
    $keyword = null
): ResponseMerchantDeposit
```

## Parameters

| Parameter | Type | Tags | Description |
|  --- | --- | --- | --- |
| `depositId` | `string` | Template, Required | Deposit Id |
| `page` | [`?Page`](../../doc/models/page.md) | Query, Optional | Use this field to specify paginate your results, by using page size and number. You can use one of the following methods:<br><br>> /endpoint?page={ "number": 1, "size": 50 }<br>> <br>> /endpoint?page[number]=1&page[size]=50 |
| `order` | [`?(Order21[])`](../../doc/models/order-21.md) | Query, Optional | Criteria used in query string parameters to order results.  Most fields from the endpoint results can be used as a `key`.  Unsupported fields or operators will return a `412`.  Must be encoded, or use syntax that does not require encoding.<br><br>> /endpoint?order[0][key]=created_ts&order[0][operator]=asc<br>> <br>> /endpoint?order=[{ "key": "created_ts", "operator": "asc"}]<br>> <br>> /endpoint?order=[{ "key": "balance", "operator": "desc"},{ "key": "created_ts", "operator": "asc"}]<br><br>**Constraints**: *Minimum Items*: `1` |
| `filterBy` | [`?(FilterBy[])`](../../doc/models/filter-by.md) | Query, Optional | Filter criteria that can be used in query string parameters.  Most fields from the endpoint results can be used as a `key`.  Unsupported fields or operators will return a `412`. Must be encoded, or use syntax that does not require encoding.<br><br>> ?filter_by[0][key]=first_name&filter_by[0][operator]==&filter_by[0][value]=Steve<br>> <br>> /endpoint?filter_by=[{ "key": "first_name", "operator": "=", "value": "Fred" }]<br>> <br>> /endpoint?filter_by=[{ "key": "account_type", "operator": "=", "value": "VISA" }]<br>> <br>> /endpoint?filter_by=[{ "key": "created_ts", "operator": ">=", "value": "946702799" }, { "key": "created_ts", "operator": "<=", value: "1695061891" }]<br>> <br>> /endpoint?filter_by=[{ "key": "last_name", "operator": "IN", "value": "Williams,Brown,Allman" }]<br><br>**Constraints**: *Minimum Items*: `1` |
| `expand` | [`?(string(Expand15Enum)[])`](../../doc/models/expand-15-enum.md) | Query, Optional | Most endpoints in the API have a way to retrieve extra data related to the current record being retrieved. For example, if the API request is for the accountvaults endpoint, and the end user also needs to know which contact the token belongs to, this data can be returned in the accountvaults endpoint request.<br><br>**Constraints**: *Unique Items Required*, *Pattern*: `^[\w]+$` |
| `format` | [`?string(Format1Enum)`](../../doc/models/format-1-enum.md) | Query, Optional | Reporting format, valid values: csv, tsv |
| `typeahead` | `?string` | Query, Optional | You can use any `field_name` from this endpoint results to order the list using the value provided as filter for the same `field_name`. It will be ordered using the following rules: 1) Exact match, 2) Starts with, 3) Contains.<br><br>> /endpoint?filter={ "field_name": "Value" }&_typeahead=field_name |
| `fields` | [`?(string(Field37Enum)[])`](../../doc/models/field-37-enum.md) | Query, Optional | You can use any `field_name` from this endpoint results to filter the list of fields returned on the response. |
| `keyword` | string\|float\|null | Query, Optional | This is a container for any-of cases. |

## Response Type

[`ResponseMerchantDeposit`](../../doc/models/response-merchant-deposit.md)

## Example Usage

```php
$depositId = 'deposit_id0';

$page = PageBuilder::init()
    ->number(1)
    ->size(50)
    ->build();

$order = [
    Order21Builder::init(
        'first_name',
        OperatorEnum::ASC
    )->build()
];

$filterBy = [
    FilterByBuilder::init(
        'first_name',
        Operator1Enum::ENUM_1,
        'Fred'
    )->build()
];

$result = $merchantDepositsController->viewSingleMerchantDeposit(
    $depositId,
    $page,
    $order,
    $filterBy
);
```

## Example Response *(as JSON)*

```json
{
  "type": "MerchantDeposit",
  "data": {
    "company_id": "8410111",
    "merchant_id": "88441",
    "deposit_types": [
      "deposit"
    ],
    "deposit_amount": 2487.24,
    "batch_amount": 2487.24,
    "adjustment_amount": 2487.24,
    "retained_amount": 2487.24,
    "conveyed_amount": 2487.24,
    "fee_amount": 2487.24,
    "reference_number": "400000",
    "trace_number": "400000",
    "currency": "USD",
    "created_ts": 1422040992,
    "reported_date": "2021-12-01",
    "transaction_date": "2021-12-01",
    "details": [
      {
        "amount": 2487.24,
        "reported_date": "2021-12-01",
        "settled_date": "2021-12-01"
      }
    ]
  }
}
```

## Errors

| HTTP Status Code | Error Description | Exception Class |
|  --- | --- | --- |
| 401 | Unauthorized | [`Response401tokenException`](../../doc/models/response-401-token-exception.md) |


# List All Merchant Deposits

```php
function listAllMerchantDeposits(
    ?Page $page = null,
    ?array $order = null,
    ?array $filterBy = null,
    ?array $expand = null,
    ?string $format = null,
    ?string $typeahead = null,
    ?array $fields = null,
    $keyword = null
): ResponseMerchantDepositsCollection
```

## Parameters

| Parameter | Type | Tags | Description |
|  --- | --- | --- | --- |
| `page` | [`?Page`](../../doc/models/page.md) | Query, Optional | Use this field to specify paginate your results, by using page size and number. You can use one of the following methods:<br><br>> /endpoint?page={ "number": 1, "size": 50 }<br>> <br>> /endpoint?page[number]=1&page[size]=50 |
| `order` | [`?(Order21[])`](../../doc/models/order-21.md) | Query, Optional | Criteria used in query string parameters to order results.  Most fields from the endpoint results can be used as a `key`.  Unsupported fields or operators will return a `412`.  Must be encoded, or use syntax that does not require encoding.<br><br>> /endpoint?order[0][key]=created_ts&order[0][operator]=asc<br>> <br>> /endpoint?order=[{ "key": "created_ts", "operator": "asc"}]<br>> <br>> /endpoint?order=[{ "key": "balance", "operator": "desc"},{ "key": "created_ts", "operator": "asc"}]<br><br>**Constraints**: *Minimum Items*: `1` |
| `filterBy` | [`?(FilterBy[])`](../../doc/models/filter-by.md) | Query, Optional | Filter criteria that can be used in query string parameters.  Most fields from the endpoint results can be used as a `key`.  Unsupported fields or operators will return a `412`. Must be encoded, or use syntax that does not require encoding.<br><br>> ?filter_by[0][key]=first_name&filter_by[0][operator]==&filter_by[0][value]=Steve<br>> <br>> /endpoint?filter_by=[{ "key": "first_name", "operator": "=", "value": "Fred" }]<br>> <br>> /endpoint?filter_by=[{ "key": "account_type", "operator": "=", "value": "VISA" }]<br>> <br>> /endpoint?filter_by=[{ "key": "created_ts", "operator": ">=", "value": "946702799" }, { "key": "created_ts", "operator": "<=", value: "1695061891" }]<br>> <br>> /endpoint?filter_by=[{ "key": "last_name", "operator": "IN", "value": "Williams,Brown,Allman" }]<br><br>**Constraints**: *Minimum Items*: `1` |
| `expand` | [`?(string(Expand15Enum)[])`](../../doc/models/expand-15-enum.md) | Query, Optional | Most endpoints in the API have a way to retrieve extra data related to the current record being retrieved. For example, if the API request is for the accountvaults endpoint, and the end user also needs to know which contact the token belongs to, this data can be returned in the accountvaults endpoint request.<br><br>**Constraints**: *Unique Items Required*, *Pattern*: `^[\w]+$` |
| `format` | [`?string(Format1Enum)`](../../doc/models/format-1-enum.md) | Query, Optional | Reporting format, valid values: csv, tsv |
| `typeahead` | `?string` | Query, Optional | You can use any `field_name` from this endpoint results to order the list using the value provided as filter for the same `field_name`. It will be ordered using the following rules: 1) Exact match, 2) Starts with, 3) Contains.<br><br>> /endpoint?filter={ "field_name": "Value" }&_typeahead=field_name |
| `fields` | [`?(string(Field38Enum)[])`](../../doc/models/field-38-enum.md) | Query, Optional | You can use any `field_name` from this endpoint results to filter the list of fields returned on the response. |
| `keyword` | string\|float\|null | Query, Optional | This is a container for any-of cases. |

## Response Type

[`ResponseMerchantDepositsCollection`](../../doc/models/response-merchant-deposits-collection.md)

## Example Usage

```php
$page = PageBuilder::init()
    ->number(1)
    ->size(50)
    ->build();

$order = [
    Order21Builder::init(
        'first_name',
        OperatorEnum::ASC
    )->build()
];

$filterBy = [
    FilterByBuilder::init(
        'first_name',
        Operator1Enum::ENUM_1,
        'Fred'
    )->build()
];

$result = $merchantDepositsController->listAllMerchantDeposits(
    $page,
    $order,
    $filterBy
);
```

## Example Response *(as JSON)*

```json
{
  "type": "MerchantDepositsCollection",
  "list": [
    {
      "company_id": "8410111",
      "merchant_id": "88441",
      "deposit_types": [
        "deposit"
      ],
      "deposit_amount": 2487.24,
      "batch_amount": 2487.24,
      "adjustment_amount": 2487.24,
      "retained_amount": 2487.24,
      "conveyed_amount": 2487.24,
      "fee_amount": 2487.24,
      "reference_number": "400000",
      "trace_number": "400000",
      "currency": "USD",
      "created_ts": 1422040992,
      "reported_date": "2021-12-01",
      "transaction_date": "2021-12-01",
      "details": [
        {
          "amount": 2487.24,
          "reported_date": "2021-12-01",
          "settled_date": "2021-12-01"
        }
      ]
    }
  ],
  "links": {
    "type": "Links",
    "first": "/v1/endpoint?page[size]=10&page[number]=1",
    "previous": "/v1/endpoint?page[size]=10&page[number]=5",
    "next": "/v1/endpoint?page[size]=10&page[number]=7",
    "last": "/v1/endpoint?page[size]=10&page[number]=42"
  },
  "pagination": {
    "type": "Pagination",
    "total_count": 423,
    "page_count": 42,
    "page_number": 6,
    "page_size": 10
  },
  "sort": {
    "type": "Sorting",
    "fields": [
      {
        "field": "last_name",
        "order": "asc"
      }
    ]
  }
}
```

## Errors

| HTTP Status Code | Error Description | Exception Class |
|  --- | --- | --- |
| 401 | Unauthorized | [`Response401tokenException`](../../doc/models/response-401-token-exception.md) |

