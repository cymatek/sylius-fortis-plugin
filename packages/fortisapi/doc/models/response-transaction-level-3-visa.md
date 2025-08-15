
# Response Transaction Level 3 Visa

## Structure

`ResponseTransactionLevel3Visa`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type115Enum)`](../../doc/models/type-115-enum.md) | Optional | Resource Type<br><br>**Default**: `Type115Enum::TRANSACTIONLEVEL3VISA` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data29`](../../doc/models/data-29.md) | Optional | - | getData(): ?Data29 | setData(?Data29 data): void |

## Example (as JSON)

```json
{
  "type": "TransactionLevel3Visa",
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

