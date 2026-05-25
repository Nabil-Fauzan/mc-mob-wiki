# 🌊 Aether Protocol: Multiversal Intelligence Network

Welcome to the **Aether Protocol Wiki**, a high-performance, cinematic intelligence terminal for Minecraft entities and environments. This platform transforms traditional documentation into an immersive, AI-powered atmospheric experience.

---

## 🚀 The AI Engine (Powered by Oracle)

### 🧠 The Oracle Assistant (Streaming SSE)
- **Real-Time Streaming**: Powered by Groq's Llama-3.3-70b, the Oracle uses Server-Sent Events (SSE) to "type" out responses in real-time.
- **The Cache Illusion**: An intelligent zero-token architecture that streams cached responses for repeated questions, simulating the real-time typing effect without consuming any API tokens.
- **Multiversal Knowledge**: Fully integrated with your database. The Oracle is aware of the registry's real-time stats, newest discoveries, and category distributions.

### 🛡️ AI Content Moderation & Curation
- **Auto-Review System**: Community contributions and field notes are instantly scanned by the AI for toxicity, lore accuracy, and profanity.
- **Automated Changelogs**: An exclusive Admin tool that reads raw developer `git log` commits, sends them to the Oracle AI, and automatically generates beautiful, Markdown-formatted Release Notes for the community.

---

## 🧬 Community & Researcher Identity

### 🌐 The Researcher Network
- **Follow & Connect**: A fully functional social networking layer allowing researchers to follow each other.
- **Dynamic Network Feed**: A dashboard feed that aggregates the latest discoveries, notes, and level-ups from the people you follow.
- **Profile Customization**: Researchers can customize their public profiles with dynamic URL/Upload banners, pinned favorite entities, and unique achievement titles.
- **3D Skin Integration**: Dynamic synchronization with the Mojang API to display 3D heads in navigation and full-body 3D renders on profiles.

### 🎮 Gamification & Reputation
- **Aether EXP System**: Earn experience points by adding comments (Field Notes) and discovering new biomes.
- **Level Scaling**: A dynamic leveling algorithm that increases difficulty as researchers progress.
- **Achievement Unlockables**: Hit milestones to unlock rare titles that can be proudly displayed on profiles.

---

## 💎 Cinematic Visual Engine

- **Aether Ocean Aesthetics**: A bespoke design system featuring glassmorphism, dynamic gradients, atmospheric blurs, and Tailwind Typography (`@tailwindcss/typography`).
- **CRT Security Terminal**: A hidden admin terminal featuring scanline effects, CRT grid overlays, glow animations, and actual command parsing.
- **Dimension-Based Theming**: The entire site's color palette shifts dynamically based on current dimensional focus or user preferences.

---

## ⚔️ Interactive Intelligence

- **The Mob Duel Simulator**: A built-in battle engine that simulates combat between entities based on actual in-game statistics (HP & DMG).
- **Interactive Radar Stats**: Visual threat analysis using interactive radar charts (Chart.js).
- **Revision History**: A robust wiki engine that tracks every contribution (Mobs/Biomes) with a complete reversion and approval system.

---

## ⌨️ Power User Interface (Keyboard-First)

- **Command Palette (Ctrl+K)**: A global search and navigation hub with full arrow-key support.
- **Protocol Overrides**: Admin-only shortcuts like `'E'` for Quick Edit directly from search results.
- **Master Override Terminal**: Accessible via the Konami Code (`↑↑↓↓←→←→BA`) or secret UI buttons.

---

## 🛡️ Fortified Security Layer

- **Title Forgery Prevention**: Strict backend validation ensuring users cannot spoof achievement titles.
- **Reputation Integrity**: Logical blocks preventing users from upvoting their own contributions.
- **Role-Based Access Control (RBAC)**: Deep authorization gates ensuring only valid `ADMIN` users can access the Master Terminal, run AI Changelogs, or revert wiki data.

---

## 🛠️ Technology Stack

- **Core Framework**: [Laravel 11](https://laravel.com)
- **AI Intelligence**: [Groq API](https://groq.com) (Llama 3.3 70b)
- **Frontend Engine**: [Tailwind CSS v3](https://tailwindcss.com) & [Alpine.js](https://alpinejs.dev)
- **Markdown & UI**: Tailwind Typography Plugin & League CommonMark
- **Data Viz & APIs**: Chart.js & Mojang/Mc-Heads API

---

## ⚡ Quick Start

1. **Clone & Install**:
   ```bash
   composer install
   npm install
   ```

2. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Don't forget to add your `GROQ_API_KEY` for The Oracle.*

3. **Database & Storage**:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

4. **Compile Assets & Launch**:
   ```bash
   npm run build
   php artisan serve
   ```

---

<p align="center">
  <i>"Mapping the impossible, one dimension at a time."</i><br>
  <b>Aether Protocol [v.4.0.0] - THE NETWORK UPDATE</b>
</p>
