
# Response Recurring

## Structure

`ResponseRecurring`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type67Enum)`](../../doc/models/type-67-enum.md) | Optional | Resource Type<br><br>**Default**: `Type67Enum::RECURRING` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data20`](../../doc/models/data-20.md) | Optional | - | getData(): ?Data20 | setData(?Data20 data): void |

## Example (as JSON)

```json
{
  "type": "Recurring",
  "data": {
    "account_vault_id": "account_vault_id6",
    "token_id": "token_id4",
    "contact_id": "contact_id4",
    "account_vault_api_id": "account_vault_api_id4",
    "token_api_id": "token_api_id6"
  }
}
```

