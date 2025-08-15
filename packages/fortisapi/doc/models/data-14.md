
# Data 14

## Structure

`Data14`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `id` | `?string` | Optional | Deposit Id | getId(): ?string | setId(?string id): void |
| `companyId` | `?string` | Optional | Company Id | getCompanyId(): ?string | setCompanyId(?string companyId): void |
| `merchantId` | `?string` | Optional | Merchant Id | getMerchantId(): ?string | setMerchantId(?string merchantId): void |
| `service` | `?string` | Optional | Service | getService(): ?string | setService(?string service): void |
| `depositTypes` | [`?(string(DepositTypeEnum)[])`](../../doc/models/deposit-type-enum.md) | Optional | - | getDepositTypes(): ?array | setDepositTypes(?array depositTypes): void |
| `depositAmount` | `?float` | Optional | Deposit Amount | getDepositAmount(): ?float | setDepositAmount(?float depositAmount): void |
| `batchAmount` | `?float` | Optional | Batch Amount | getBatchAmount(): ?float | setBatchAmount(?float batchAmount): void |
| `adjustmentAmount` | `?float` | Optional | Adjustment Amount | getAdjustmentAmount(): ?float | setAdjustmentAmount(?float adjustmentAmount): void |
| `retainedAmount` | `?float` | Optional | Retained Amount | getRetainedAmount(): ?float | setRetainedAmount(?float retainedAmount): void |
| `conveyedAmount` | `?float` | Optional | Conveyed Amount | getConveyedAmount(): ?float | setConveyedAmount(?float conveyedAmount): void |
| `feeAmount` | `?float` | Optional | Fee Amount | getFeeAmount(): ?float | setFeeAmount(?float feeAmount): void |
| `referenceNumber` | `?string` | Optional | Reference Number | getReferenceNumber(): ?string | setReferenceNumber(?string referenceNumber): void |
| `traceNumber` | `?string` | Optional | - | getTraceNumber(): ?string | setTraceNumber(?string traceNumber): void |
| `currency` | `?string` | Optional | Currency | getCurrency(): ?string | setCurrency(?string currency): void |
| `createdTs` | `?int` | Optional | Created Timestamp | getCreatedTs(): ?int | setCreatedTs(?int createdTs): void |
| `reportedDate` | `?string` | Optional | Reported Date<br><br>**Constraints**: *Maximum Length*: `10`, *Pattern*: `^[\d]{4}-[\d]{2}-[\d]{2}$` | getReportedDate(): ?string | setReportedDate(?string reportedDate): void |
| `transactionDate` | `?string` | Optional | Transaction Date<br><br>**Constraints**: *Maximum Length*: `10`, *Pattern*: `^[\d]{4}-[\d]{2}-[\d]{2}$` | getTransactionDate(): ?string | setTransactionDate(?string transactionDate): void |
| `depositAccount` | `?string` | Optional | Deposit Account | getDepositAccount(): ?string | setDepositAccount(?string depositAccount): void |
| `details` | [`?(Detail2[])`](../../doc/models/detail-2.md) | Optional | - | getDetails(): ?array | setDetails(?array details): void |

## Example (as JSON)

```json
{
  "company_id": "8410111",
  "merchant_id": "88441",
  "deposit_amount": 2487.24,
  "batch_amount": 2487.24,
  "adjustment_amount": 2487.24,
  "retained_amount": 2487.24,
  "conveyed_amount": 2487.24,
  "fee_amount": 2487.24,
  "reference_number": "400000",
  "trace_number": "400000",
  "currency": "USD",
  "created_ts": 1422040992,
  "reported_date": "2021-12-01",
  "transaction_date": "2021-12-01",
  "id": "id0",
  "service": "service0",
  "deposit_types": [
    "deposit",
    "adjustment",
    "fee"
  ]
}
```

