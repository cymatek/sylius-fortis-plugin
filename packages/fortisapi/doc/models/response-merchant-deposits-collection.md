
# Response Merchant Deposits Collection

## Structure

`ResponseMerchantDepositsCollection`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type48Enum)`](../../doc/models/type-48-enum.md) | Optional | Resource Type<br><br>**Default**: `Type48Enum::MERCHANTDEPOSITSCOLLECTION` | getType(): ?string | setType(?string type): void |
| `list` | [`?(List8[])`](../../doc/models/list-8.md) | Optional | Resource Members | getList(): ?array | setList(?array list): void |
| `links` | [`?Links`](../../doc/models/links.md) | Optional | Pagination page links | getLinks(): ?Links | setLinks(?Links links): void |
| `pagination` | [`?Pagination`](../../doc/models/pagination.md) | Optional | Pagination info | getPagination(): ?Pagination | setPagination(?Pagination pagination): void |
| `sort` | [`?Sort`](../../doc/models/sort.md) | Optional | Sort information used on the results | getSort(): ?Sort | setSort(?Sort sort): void |

## Example (as JSON)

```json
{
  "type": "MerchantDepositsCollection",
  "list": [
    {
      "id": "id2",
      "company_id": "company_id8",
      "merchant_id": "merchant_id2",
      "service": "service8",
      "deposit_types": [
        "fee",
        "deposit"
      ]
    }
  ],
  "links": {
    "type": "Links",
    "first": "first0",
    "previous": "previous2",
    "next": "next2",
    "last": "last4"
  },
  "pagination": {
    "type": "Pagination",
    "total_count": 100,
    "page_count": 212,
    "page_number": 28,
    "page_size": 6
  },
  "sort": {
    "type": "Sorting",
    "fields": [
      {
        "field": "field2",
        "order": "asc"
      },
      {
        "field": "field2",
        "order": "asc"
      },
      {
        "field": "field2",
        "order": "asc"
      }
    ]
  }
}
```

