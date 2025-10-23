# Stack de Monitoreo (Gratis, Local): Loki + Promtail + Prometheus + Grafana

## Pasos rápidos
1) Copia esta carpeta `lootsy_monitoring` junto a tu proyecto Laravel o donde prefieras.
2) Edita `docker-compose.yml` y reemplaza `/ABSOLUTE/PATH/TO/lootsy/storage/logs` con la **ruta absoluta** a `storage/logs` de tu proyecto.
   - En macOS/Linux puedes obtenerla con `pwd` dentro de tu proyecto.
3) Asegúrate de que tu app expone `GET /metrics` y que corre en `http://localhost:8000` (o ajusta `prometheus.yml`).
4) Levanta el stack:
   ```bash
   docker compose up -d
   ```
5) Abre:
   - Grafana: http://localhost:3000 (admin / admin)
   - Prometheus: http://localhost:9090
   - Loki es interno (agrega datasource en Grafana con URL `http://loki:3100`).

## Dashboards rápidos
- **Métricas (Prometheus):**
  - Items agregados: `rate(cart_items_added_total[5m])`
  - Items eliminados: `rate(cart_items_removed_total[5m])`
  - Tamaño carrito: `cart_size_current`
- **Logs (Loki):**
  - Errores: `{app="laravel"} | json | level =~ "ERROR|CRITICAL"`

## Alertas (Grafana Alerting)
- Pico de errores en logs (Loki):
  ```
  sum(count_over_time({app="laravel"} | json | level =~ "ERROR|CRITICAL" [5m]))
  ```
  Condición: `> 5` por 5m
- Caída de uso (Prometheus):
  ```
  rate(cart_items_added_total[5m])
  ```
  Condición: `< 0.1` por 10m
