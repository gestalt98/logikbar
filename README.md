# logikbar

General-purpose top-level repo for [logikbar.com](https://logikbar.com).

## Layout

- `infrastructure/` — deployment and platform config
- `models/` — shared model sources (e.g. trust_rapport)
- `yxchange/` — yxchange application code

## Local development

```bash
cd ~/Workspaces/gestalt98-git/logikbar
python3 -m venv .venv
source .venv/bin/activate
pip install -U pip
# pip install -r requirements.txt   # when added
cp .env.example .env
```

Related repos (cloned alongside this one):

- `../benebench`
- `../trust_rapport`
- `../yxchange`
