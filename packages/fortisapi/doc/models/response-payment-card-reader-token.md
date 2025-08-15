
# Response Payment Card Reader Token

## Structure

`ResponsePaymentCardReaderToken`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type58Enum)`](../../doc/models/type-58-enum.md) | Optional | Resource Type<br><br>**Default**: `Type58Enum::PAYMENTCARDREADERTOKEN` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data17`](../../doc/models/data-17.md) | Optional | - | getData(): ?Data17 | setData(?Data17 data): void |

## Example (as JSON)

```json
{
  "type": "PaymentCardReaderToken",
  "data": {
    "token": "token4"
  }
}
```

