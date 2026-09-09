# GitHub Copilot Workflow & Engineering Instructions for Harness Standard

## 1. Operating Workflow & Interaction Protocol (Strict Enforcement)
Copilot must adhere to a strict 4-phase protocol for all requests requiring code changes, refactoring, or pipeline updates:

### Phase 1: Methodology & Explanation (First Turn)
- Explain the technical rationale, architectural considerations, and Harness-specific conventions that apply to the request.
- Detail the trade-offs and constraints before proposing implementations.

### Phase 2: Proposal & User Confirmation (No Auto-execution)
- Provide 2–3 distinct actionable implementation options (e.g., Option A: Minimalist/Fast vs. Option B: Scalable/Full Canary).
- Stop and wait for the user's explicit confirmation and selection before generating functional codebase modifications.

### Phase 3: Execution & Impact Analysis (Post-Approval)
- Once the user confirms the plan, output the exact code changes.
- Concurrently conduct an Impact Assessment covering:
  - Breaking changes or regression risks on other modules/services.
  - Dependencies on downstream Harness stages, connectors, or environment variables.
  - Compatibility with existing automated test suites.

### Phase 4: Change Summary & Verification
- Summarize what was changed, file-by-file.
- Provide clear verification commands (unit tests, smoke tests, health probes) to confirm successful deployment without regressions.

---

## 2. Harness & Engineering Standards
- **Secrets Hygiene:** Never output plaintext credentials or tokens. Always utilize `<+secrets.getValue("...")>` or system environment variables.
- **Idempotency & Safety:** All automation scripts must be re-runnable without unintended side effects. Scripts must include defensive flags (`set -euo pipefail`).
- **Observability:** Emit structured logs to support Harness Automated Verification and Log Analysis.
- **Pipeline Integrity:** Pin all container tags (avoid `latest`) and define explicit resource requests/limits (`cpu`, `memory`).
- **Feature Flags (Harness FF):** Ensure safe fallback defaults and attach explicit context targets (`Target(identifier=..., attributes=...)`).
- **Health Probes:** Always include or maintain `/healthz` and `/ready` endpoints to safeguard Harness Canary and Blue/Green rollout/rollback mechanisms.