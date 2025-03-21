export function formatDate(dateString: string | null): string {
  if (!dateString) return '';
  const date = new Date(dateString);

  const year = date.getUTCFullYear();
  const month = date.toLocaleString('en-US', { month: 'short', timeZone: 'UTC' });
  const day = String(date.getUTCDate()).padStart(2, '0');
  const hours = String(date.getUTCHours()).padStart(2, '0');
  const minutes = String(date.getUTCMinutes()).padStart(2, '0');

  return `${month} ${day}, ${year}, ${hours}:${minutes}`;
}
