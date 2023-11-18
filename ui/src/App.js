import Button from "@mui/material/Button";
import Header from "./components/Header/Header";
import { ThemeProvider, createTheme } from "@mui/material/styles";
import CssBaseline from "@mui/material/CssBaseline";
import { QueryClient, QueryClientProvider, useQuery } from "react-query";
import "./index.css";
import Dashboard from "./pages/Dashboard/Dashboard";

const queryClient = new QueryClient();

const darkTheme = createTheme({
  palette: {
    mode: "dark",
  },
});

function App() {
  return (
    <ThemeProvider theme={darkTheme}>
      <CssBaseline />
      <QueryClientProvider client={queryClient}>
        <Header>
          <Dashboard />
        </Header>
      </QueryClientProvider>
    </ThemeProvider>
  );
}

export default App;
