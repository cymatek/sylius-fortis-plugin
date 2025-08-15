
# Response User

## Structure

`ResponseUser`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type129Enum)`](../../doc/models/type-129-enum.md) | Optional | Resource Type<br><br>**Default**: `Type129Enum::USER` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data34`](../../doc/models/data-34.md) | Optional | - | getData(): ?Data34 | setData(?Data34 data): void |

## Example (as JSON)

```json
{
  "type": "User",
  "data": {
    "account_number": "account_number0",
    "branding_domain_url": "branding_domain_url0",
    "cell_phone": "cell_phone6",
    "company_name": "company_name6",
    "contact_id": "contact_id4"
  }
}
```

