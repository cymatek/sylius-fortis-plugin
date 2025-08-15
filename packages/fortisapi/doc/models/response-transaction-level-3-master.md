
# Response Transaction Level 3 Master

## Structure

`ResponseTransactionLevel3Master`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type114Enum)`](../../doc/models/type-114-enum.md) | Optional | Resource Type<br><br>**Default**: `Type114Enum::TRANSACTIONLEVEL3MASTER` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data29`](../../doc/models/data-29.md) | Optional | - | getData(): ?Data29 | setData(?Data29 data): void |

## Example (as JSON)

```json
{
  "type": "TransactionLevel3Master",
  "data": {
    "id": "id0",
    "transaction_id": "transaction_id8",
    "level3_data": {
      "destination_country_code": "destination_country_code4",
      "duty_amount": 182,
      "freight_amount": 60,
      "national_tax": 999999998900,
      "sales_tax": 999999998900
    }
  }
}
```

