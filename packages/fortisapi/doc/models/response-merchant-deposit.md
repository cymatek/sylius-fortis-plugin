
# Response Merchant Deposit

## Structure

`ResponseMerchantDeposit`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type47Enum)`](../../doc/models/type-47-enum.md) | Optional | Resource Type<br><br>**Default**: `Type47Enum::MERCHANTDEPOSIT` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data14`](../../doc/models/data-14.md) | Optional | - | getData(): ?Data14 | setData(?Data14 data): void |

## Example (as JSON)

```json
{
  "type": "MerchantDeposit",
  "data": {
    "id": "id0",
    "company_id": "company_id6",
    "merchant_id": "merchant_id0",
    "service": "service0",
    "deposit_types": [
      "deposit",
      "adjustment",
      "fee"
    ]
  }
}
```

