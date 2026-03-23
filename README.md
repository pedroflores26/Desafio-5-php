# 🎲 Atividades em PHP — Gerador de Rifas & Jogo Jokempô

## 📌 Descrição

Este projeto reúne duas atividades práticas desenvolvidas em PHP, com foco em lógica de programação, interação com formulários e geração dinâmica de conteúdo.

As atividades são:
- 🎟️ Gerador de Rifas
- ✊ Jogo Jokempô (Pedra, Papel e Tesoura)

---

# 🎯 Objetivo

Praticar:
- Uso de formulários com `$_POST`
- Estruturas de repetição (`for`)
- Estruturas condicionais (`if/else`, `switch`)
- Funções em PHP
- Geração dinâmica de conteúdo
- Estilização com CSS

---

# 🎟️ Atividade 1 — Gerador de Rifas

## 📌 Objetivo
Criar um sistema que gere automaticamente bilhetes de rifa numerados.

## ⚙️ Funcionalidades
- Definir quantidade de bilhetes
- Inserir:
  - Nome da campanha
  - Nome do prêmio
  - Valor da rifa
- Gerar lista de bilhetes numerados (ex: 001, 002, 003...)
- Botão para impressão
- Estilização visual dos bilhetes

## 🧠 Lógica utilizada
- Uso de `for` para gerar os números
- Uso de `str_pad()` para formatar com zeros à esquerda

```php
str_pad($numero, 3, "0", STR_PAD_LEFT);
