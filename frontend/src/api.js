export async function createRoute(data) {
  const res = await fetch("/api/routes/create_route.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data)
  });
  return res.json();
}

export async function fetchAnalytics(params = {}) {
  const query = new URLSearchParams(params).toString();
  const res = await fetch("/api/analytics?" + query);
  return res.json();
}
