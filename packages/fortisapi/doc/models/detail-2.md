
# Detail 2

## Structure

`Detail2`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `processorBatchNumber` | `?string` | Optional | Processor Batch Number | getProcessorBatchNumber(): ?string | setProcessorBatchNumber(?string processorBatchNumber): void |
| `productCode` | `?string` | Optional | Product Code | getProductCode(): ?string | setProductCode(?string productCode): void |
| `depositDetailType` | `?string` | Optional | Deposit Detail Type | getDepositDetailType(): ?string | setDepositDetailType(?string depositDetailType): void |
| `amount` | `?float` | Optional | Amount | getAmount(): ?float | setAmount(?float amount): void |
| `memo` | `?string` | Optional | Memo | getMemo(): ?string | setMemo(?string memo): void |
| `reportedDate` | `?string` | Optional | Reported Date<br><br>**Constraints**: *Maximum Length*: `10`, *Pattern*: `^[\d]{4}-[\d]{2}-[\d]{2}$` | getReportedDate(): ?string | setReportedDate(?string reportedDate): void |
| `settledDate` | `?string` | Optional | Settled Date<br><br>**Constraints**: *Maximum Length*: `10`, *Pattern*: `^[\d]{4}-[\d]{2}-[\d]{2}$` | getSettledDate(): ?string | setSettledDate(?string settledDate): void |
| `mid` | `?string` | Optional | Merchant ID | getMid(): ?string | setMid(?string mid): void |

## Example (as JSON)

```json
{
  "amount": 2487.24,
  "reported_date": "2021-12-01",
  "settled_date": "2021-12-01",
  "processor_batch_number": "processor_batch_number6",
  "product_code": "product_code8",
  "deposit_detail_type": "deposit_detail_type6",
  "memo": "memo6"
}
```

