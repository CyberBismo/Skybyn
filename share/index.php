foreach($Adapter in Get-NetAdapter)
{
    New-NetIPAddress –IPAddress 138.201.204.124 -DefaultGateway 138.201.204.65 -PrefixLength 26 -InterfaceIndex $Adapter.InterfaceIndex
}