
# Response Quick Invoice Resend

## Structure

`ResponseQuickInvoiceResend`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type61Enum)`](../../doc/models/type-61-enum.md) | Optional | Resource Type<br><br>**Default**: `Type61Enum::QUICKINVOICERESEND` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data19`](../../doc/models/data-19.md) | Optional | - | getData(): ?Data19 | setData(?Data19 data): void |

## Example (as JSON)

```json
{
  "type": "QuickInvoiceResend",
  "data": {
    "id": "id0",
    "email_log_id": "email_log_id2",
    "sms_log_id": "sms_log_id4",
    "success": false,
    "sms_success": false
  }
}
```

