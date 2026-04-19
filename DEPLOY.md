# EXPASS Deck — デプロイ手順

`https://expass.app/deck/` で公開するまでの手順です。

## 前提

- Mac で `~/Desktop/livepass/` に EXPASS のリポジトリがクローン済み
- `git pull && npx vercel --prod` で緊急デプロイができる状態

## 手順

### 1. ファイルを配置する

Mac のターミナルを開いて:

```bash
cd ~/Desktop/livepass

# 最新を取得
git pull

# deck フォルダを public 配下に作成
mkdir -p public/deck
```

次に、このチャットから落とした以下の3ファイルを `~/Desktop/livepass/public/deck/` に入れる:

- `index.html`
- `logo.png`
- `cassette.png`

Finder でドラッグ&ドロップで OK。

### 2. コミット・プッシュ

```bash
cd ~/Desktop/livepass
git add public/deck
git commit -m "Add EXPASS deck for events and investor sharing"
git push origin main
```

GitHub → Vercel の自動デプロイで、数分後に `https://expass.app/deck/` が見られるようになる。

### 3. 確認

ブラウザで `https://expass.app/deck/` を開く。

もし 404 が出る場合は、Vercel の自動デプロイがまだ走っていない可能性があるので、緊急ルートで:

```bash
cd ~/Desktop/livepass
npx vercel --prod
```

## ファイル構成(公開後)

```
https://expass.app/deck/                ← メイン
https://expass.app/deck/index.html      ← 同じ
https://expass.app/deck/logo.png        ← ロゴ画像
https://expass.app/deck/cassette.png    ← カセット写真
```

## QR コード

生成済み(同じフォルダに):

- `qr_simple.png` — シンプルな QR (白背景)
- `qr_dark.png` — ダークテーマ QR (黒背景)
- `qr_card.png` — ブランドカード付き (白) — プレゼン・名刺サイズ
- `qr_card_dark.png` — ブランドカード付き (黒) — ナイトイベント・ポスター

## 使い方のコツ

- **姉・投資家への送付**: URL をそのままメッセージで送る
- **イベント会場**: `qr_card_dark.png` をプリントアウトして会場入口や受付に設置
- **SNS 投稿**: `qr_card.png` (白背景) を投稿画像として使う
- **プレゼン**: 最後のスライドに `qr_card.png` を貼って、質疑応答中にスキャンしてもらう

## 更新するとき

将来、内容を直したくなったら:

1. `public/deck/index.html` を編集
2. `git add . && git commit -m "Update deck" && git push`
3. 数分待てば反映される

URL はずっと同じなので、QR コードは一度印刷すればずっと使える。
