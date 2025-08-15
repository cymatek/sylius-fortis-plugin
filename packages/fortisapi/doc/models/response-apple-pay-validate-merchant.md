
# Response Apple Pay Validate Merchant

## Structure

`ResponseApplePayValidateMerchant`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type136Enum)`](../../doc/models/type-136-enum.md) | Optional | Resource Type<br><br>**Default**: `Type136Enum::APPLEPAYVALIDATEMERCHANT` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data37`](../../doc/models/data-37.md) | Optional | - | getData(): ?Data37 | setData(?Data37 data): void |

## Example (as JSON)

```json
{
  "type": "ApplePayValidateMerchant",
  "data": {
    "merchantSession": "merchantSession0"
  }
}
```

